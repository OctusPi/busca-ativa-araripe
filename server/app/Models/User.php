<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Casts\Json;
use App\Providers\Modules;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const S_ACTIVE = 1;
    const S_INACTIVE = 0;

    const P_ADMIN = 1;
    const p_MANAGER = 2;
    const P_TECGUY = 3;
    const P_SECRETARY = 4;
    const P_TEACHER = 5;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'username',
        'password',
        'profile',
        'modules',
        'organs',
        'schools',
        'passchange',
        'token',
        'lastlogin',
        'nowlogin',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => ['required', Rule::unique('users', 'email')->ignore($this->id)],
            'profile' => 'required',
            'status' => 'required'
        ];
    }


    public function messages(): array
    {
        return [
            'required' => 'Campos obrigatórios não informados...',
            'unique' => 'Já existe um usuário vinculado ao e-mail informado...'
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'organs' => Json::class,
            'schools' => Json::class,
            'modules' => Json::class
        ];
    }

    public static function list_profiles(?int $id = null): null|string|array
    {
        $profiles = [
            ['id' => self::P_ADMIN, 'title' => 'Administrador'],
            ['id' => self::p_MANAGER, 'title' => 'Gestor'],
            ['id' => self::P_TECGUY, 'title' => 'Técnico'],
            ['id' => self::P_SECRETARY, 'title' => 'Secretário(a)'],
            ['id' => self::P_TEACHER, 'title' => 'Professor(a)'],
        ];

        if(!is_null($id)) {
            foreach($profiles as $p) {
                if($p['id'] == $id) {
                    return $p['title'];
                }
            }

            return null;
        }

        return $profiles;
    }

    public static function list_modules(): array
    {
        return Modules::getModules();
    }

    public static function list_status(): array
    {
        return [
            ['id' => self::S_ACTIVE, 'title' => 'Ativo'],
            ['id' => self::S_INACTIVE, 'title' => 'Bloqueado']
        ];
    }
}
