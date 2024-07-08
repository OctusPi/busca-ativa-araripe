<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use App\Casts\Json;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected string $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'token',
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
            ['id' => BaseModel::P_ADMIN, 'title' => 'Adminsitrador'],
            ['id' => BaseModel::P_MANAGER, 'title' => 'Gestor Municipal'],
            ['id' => BaseModel::P_TECHNICIAN, 'title' => 'Agente Municipal'],
            ['id' => BaseModel::P_DIRECTOR, 'title' => 'Diretor Escolar'],
            ['id' => BaseModel::P_SECRETARY, 'title' => 'Secretário Escolar']
        ];
    }

    public static function list_modules():array
    {
        return [
            ['id' => BaseModel::M_INITIAL, 'title' => 'Acesso Inicial'],
            ['id' => BaseModel::M_MANAGER, 'title' => 'Gestão'],
            ['id' => BaseModel::M_USERS, 'title' => 'Cadastro de Usuários'],
            ['id' => BaseModel::M_ORGANS, 'title' => 'Gestão de Orgão'],
            ['id' => BaseModel::M_SCHOOLS, 'title' => 'Cadastro de Escolas'],
            ['id' => BaseModel::M_SERIES, 'title' => 'Gerenciamento de Séries/Anos'],
            ['id' => BaseModel::M_CLASSES, 'title' => 'Cadastro de Turmas'],
            ['id' => BaseModel::M_SUBJECTS, 'title' => 'Cadastro de Disciplinas'],
            ['id' => BaseModel::M_STUDENTS, 'title' => 'Gestão de Estudantes'],
            ['id' => BaseModel::M_STUDENTS_REG, 'title' => 'Registro de Matrículas'],
            ['id' => BaseModel::M_TEACHERS, 'title' => 'Cadastro de Professores'],
            ['id' => BaseModel::M_GRIDS, 'title' => 'Gestão de Grade Educacional'],
            ['id' => BaseModel::M_FREQUENCIES, 'title' => 'Registro de Frequencias']
        ];
    }
}
