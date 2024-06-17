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

    const P_ADMIN = 1;
    const P_MANAGER = 2;
    const P_TECHNICIAN = 3;
    const P_DIRECTOR = 4;
    const P_SECRETARY = 5;
    
    const M_INITIAL = 0;
    const M_MANAGER = 1;
    const M_USERS = 3;
    const M_ORGANS = 4;
    const M_SCHOOLS = 5;
    const M_SERIES = 6;
    const M_CLASSES = 7;
    const M_SUBJECTS = 8;
    const M_STUDENTS = 9;
    const M_STUDENTS_REG = 10;
    const M_TEACHERS = 11;
    const M_GRIDS = 12;
    const M_FREQUENCIES = 13;

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
            'organs' => Json::class
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
            ['id' => self::P_ADMIN, 'title' => 'Adminsitrador'],
            ['id' => self::P_MANAGER, 'title' => 'Gestor Municipal'],
            ['id' => self::P_TECHNICIAN, 'title' => 'Agente Municipal'],
            ['id' => self::P_DIRECTOR, 'title' => 'Diretor Escolar'],
            ['id' => self::P_SECRETARY, 'title' => 'Secretário Escolar']
        ];
    }

    public static function list_modules():array
    {
        return [
            ['id' => self::M_INITIAL, 'title' => 'Acesso Inicial'],
            ['id' => self::M_MANAGER, 'title' => 'Gestão'],
            ['id' => self::M_USERS, 'title' => 'Cadastro de Usuários'],
            ['id' => self::M_ORGANS, 'title' => 'Gestão de Orgão'],
            ['id' => self::M_SCHOOLS, 'title' => 'Cadastro de Escolas'],
            ['id' => self::M_SERIES, 'title' => 'Gerenciamento de Séries/Anos'],
            ['id' => self::M_CLASSES, 'title' => 'Cadastro de Turmas'],
            ['id' => self::M_SUBJECTS, 'title' => 'Cadastro de Disciplinas'],
            ['id' => self::M_STUDENTS, 'title' => 'Gestão de Estudantes'],
            ['id' => self::M_STUDENTS_REG, 'title' => 'Registro de Matrículas'],
            ['id' => self::M_TEACHERS, 'title' => 'Cadastro de Professores'],
            ['id' => self::M_GRIDS, 'title' => 'Gestão de Grade Educacional'],
            ['id' => self::M_FREQUENCIES, 'title' => 'Registro de Frequencias']
        ];
    }
}
