<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Validation\Rule;
use App\Casts\Json;

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

    public static function list_profiles():array
    {
        return [
            ['id' => parent::P_ADMIN, 'title' => 'Adminsitrador'],
            ['id' => parent::P_MANAGER, 'title' => 'Gestor Municipal'],
            ['id' => parent::P_TECHNICIAN, 'title' => 'Agente Municipal'],
            ['id' => parent::P_DIRECTOR, 'title' => 'Diretor Escolar'],
            ['id' => parent::P_SECRETARY, 'title' => 'Secretário Escolar']
        ];
    }

    public static function list_modules():array
    {
        return [
            ['id' => parent::M_INITIAL, 'title' => 'Acesso Inicial'],
            ['id' => parent::M_MANAGER, 'title' => 'Gestão'],
            ['id' => parent::M_USERS, 'title' => 'Cadastro de Usuários'],
            ['id' => parent::M_ORGANS, 'title' => 'Gestão de Orgão'],
            ['id' => parent::M_SCHOOLS, 'title' => 'Cadastro de Escolas'],
            ['id' => parent::M_SERIES, 'title' => 'Gerenciamento de Séries/Anos'],
            ['id' => parent::M_CLASSES, 'title' => 'Cadastro de Turmas'],
            ['id' => parent::M_SUBJECTS, 'title' => 'Cadastro de Disciplinas'],
            ['id' => parent::M_STUDENTS, 'title' => 'Gestão de Estudantes'],
            ['id' => parent::M_STUDENTS_REG, 'title' => 'Registro de Matrículas'],
            ['id' => parent::M_TEACHERS, 'title' => 'Cadastro de Professores'],
            ['id' => parent::M_GRIDS, 'title' => 'Gestão de Grade Educacional'],
            ['id' => parent::M_FREQUENCIES, 'title' => 'Registro de Frequencias']
        ];
    }
}
