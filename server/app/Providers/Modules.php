<?php
namespace App\Providers;

class Modules
{
    const M_INITIAL = 'initial';
    const M_FREQ = 'frequencies';
    const M_GRIDS = 'series';
    const M_SERIES = 'classes';
    const M_CLASSES = 'subjects';
    const M_SUBJECTS = 'grids';
    const M_TEACHERS = 'teachers';
    const M_STUDENTS = 'students';
    const M_REGS = 'registrations';
    const M_MANAGER = 'manager';
    const M_ORGANS = 'organs';
    const M_SCHOOLS = 'schools';
    const M_USERS = 'users';
    const M_REPORTS = 'reports';

    public static function getModules():array
    {
        return [
            ['id' => 0, 'title' => 'Acesso Inicial', 'module' => self::M_INITIAL],
            ['id' => 1, 'title' => 'Frequência', 'module' => self::M_FREQ],
            ['id' => 2, 'title' => 'Séries', 'module' => self::M_SERIES],
            ['id' => 3, 'title' => 'Turmas', 'module' => self::M_CLASSES],
            ['id' => 4, 'title' => 'Disciplinas', 'module' => self::M_SUBJECTS],
            ['id' => 5, 'title' => 'Grade', 'module' => self::M_GRIDS],
            ['id' => 6, 'title' => 'Professores', 'module' => self::M_TEACHERS],
            ['id' => 7, 'title' => 'Alunos', 'module' => self::M_STUDENTS],
            ['id' => 8, 'title' => 'Matrículas', 'module' => self::M_REGS],
            ['id' => 9, 'title' => 'Gestão', 'module' => self::M_MANAGER],
            ['id' => 10, 'title' => 'Orgãos', 'module' => self::M_ORGANS],
            ['id' => 11, 'title' => 'Escolas', 'module' => self::M_SCHOOLS],
            ['id' => 12, 'title' => 'Usuários', 'module' => self::M_USERS],
            ['id' => 13, 'title' => 'Relatórios', 'module' => self::M_REPORTS]
        ];
    }
}