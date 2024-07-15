<?php

namespace App\Models;

use App\Casts\Json;
use App\Security\Common;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'active_token',
        'recover_token',
        'organs',
        'schools',
        'profile',
        'modules',
        'passchange',
        'status',
        'lastlogin',
        'nowlogin'
    ];

    protected $hidden = [
        'password'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'organs'   => Json::class,
            'schools'  => Json::class,
            'modules'  => Json::class
        ];
    }

    public function profile():Attribute
    {
        return Attribute::make(
            get: fn(?int $value) => self::list_profiles($value) ?? ''
        );
    }

    public static function validateFields(?int $id = null):array
    {
        return [
            'name'   => 'required',
            'email'  => ['required', 'email', Rule::unique('users')->ignore($id)],
            'profile' => 'required',
            'status' => 'required'
        ];
    }

    public static function validateMsg():array
    {
        return [
            'required' => 'Campo obrigatório não informado!',
            'email'    => 'Informe um email válido!',
            'unique'   => 'Usuário já registrado no sistema!'
        ];
    }

    public static function list_profiles(?int $id = null):string|array|null
    {
        $profiles = [
            ['id' => Common::P_ADMIN, 'title' => 'Adminsitrador'],
            ['id' => Common::P_MANAGER, 'title' => 'Gestor Municipal'],
            ['id' => Common::P_TECHNICIAN, 'title' => 'Agente Municipal'],
            ['id' => Common::P_DIRECTOR, 'title' => 'Diretor Escolar'],
            ['id' => Common::P_SECRETARY, 'title' => 'Secretário Escolar']
        ];

        if($id){
            return array_filter($profiles, function($item) use($id){
                return $item['id'] === $id;
            });
        }

        return $profiles;
    }

    public static function list_modules():array
    {
        return [
            Common::M_INITIAL,
            Common::M_MANAGER,
            Common::M_USERS,
            Common::M_ORGANS,
            Common::M_SCHOOLS,
            Common::M_SERIES,
            Common::M_CLASSES,
            Common::M_SUBJECTS,
            Common::M_STUDENTS,
            Common::M_STUDENTS_REG,
            Common::M_TEACHERS,
            Common::M_GRIDS,
            Common::M_FREQUENCIES,
            Common::M_REPOSRTS
        ];
    }
}
