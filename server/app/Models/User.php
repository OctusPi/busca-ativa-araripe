<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Casts\Json;
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

    const M_INITIAL = 0;
    const M_FREQ = 1;
    const M_GRIDS = 2;
    const M_SERIES = 3;
    const M_CLASSES = 4;
    const M_SUBJECTS = 5;
    const M_TEACHERS = 6;
    const M_STUDENTS = 7;
    const M_REGS = 8;
    const M_MANAGER = 9;
    const M_ORGANS = 10;
    const M_SCHOOLS = 11;
    const M_USERS = 12;
    const M_REPORTS = 13;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'units' => Json::class,
            'modules' => Json::class
        ];
    }
    public static function list_profiles(): array
    {
        return [
            self::P_ADMIN => 'Administrador',
            self::p_MANAGER => 'Gestor',
            self::P_TECGUY => 'Técnico',
        ];
    }

    public static function list_modules(): array
    {
        return [
            ['id' => self::M_INITIAL, 'title' => 'Acesso Inicial', 'module' => 'initial'],
            ['id' => self::M_FREQ, 'title' => 'Frequência', 'module' => 'frequencies'],
            ['id' => self::M_SERIES, 'title' => 'Séries', 'module' => 'series'],
            ['id' => self::M_CLASSES, 'title' => 'Turmas', 'module' => 'classes'],
            ['id' => self::M_SUBJECTS, 'title' => 'Disciplinas', 'module' => 'subjects'],
            ['id' => self::M_GRIDS, 'title' => 'Grade', 'module' => 'grids'],
            ['id' => self::M_TEACHERS, 'title' => 'Professores', 'module' => 'teachers'],
            ['id' => self::M_STUDENTS, 'title' => 'Alunos', 'module' => 'students'],
            ['id' => self::M_REGS, 'title' => 'Matrículas', 'module' => 'registrations'],
            ['id' => self::M_MANAGER, 'title' => 'Gestão', 'module' => 'manager'],
            ['id' => self::M_ORGANS, 'title' => 'Orgãos', 'module' => 'organs'],
            ['id' => self::M_SCHOOLS, 'title' => 'Escolas', 'module' => 'schools'],
            ['id' => self::M_USERS, 'title' => 'Usuários', 'module' => 'users'],
            ['id' => self::M_REPORTS, 'title' => 'Relatórios', 'module' => 'reports']
        ];
    }
}
