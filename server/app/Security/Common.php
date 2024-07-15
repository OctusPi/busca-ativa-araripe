<?php

namespace App\Security;



class Common
{

    // consts profile
    public const P_ADMIN = 1;
    public const P_MANAGER = 2;
    public const P_TECHNICIAN = 3;
    public const P_DIRECTOR = 4;
    public const P_SECRETARY = 5;
    
    // consts modules
    public const M_INITIAL = ['id' => 0, 'title' => 'Acesso Inicial', 'module' => 'initial'];
    public const M_MANAGER = ['id' => 1, 'title' => 'Gestão', 'module' => 'manager'];
    public const M_USERS = ['id' => 3, 'title' => 'Cadastro de Usuários', 'module' => 'users'];
    public const M_ORGANS = ['id' => 4, 'title' => 'Gestão de Orgão', 'module' => 'organs'];
    public const M_SCHOOLS = ['id' => 5, 'title' => 'Cadastro de Escolas', 'module' => 'schools'];
    public const M_SERIES = ['id' => 6, 'title' => 'Gerenciamento de Séries/Anos', 'module' => 'series'];
    public const M_CLASSES = ['id' => 7, 'title' => 'Cadastro de Turmas', 'module' => 'classes'];
    public const M_SUBJECTS = ['id' => 8, 'title' => 'Cadastro de Disciplinas', 'module' => 'subjects'];
    public const M_STUDENTS = ['id' => 9, 'title' => 'Gestão de Estudantes', 'module' => 'students'];
    public const M_STUDENTS_REG = ['id' => 10, 'title' => 'Registro de Matrículas', 'module' => 'students_reg'];
    public const M_TEACHERS = ['id' => 11, 'title' => 'Cadastro de Professores', 'module' => 'teachers'];
    public const M_GRIDS = ['id' => 12, 'title' => 'Gestão de Grade Educacional', 'module' => 'grids'];
    public const M_FREQUENCIES = ['id' => 13, 'title' => 'Registro de Frequencias', 'module' => 'frequencies'];
    public const M_REPOSRTS = ['id' => 14, 'title' => 'Relatórios', 'module' => 'reports'];

    // consts status value
    public const S_INACTIVE = 0;
    public const S_ACTIVE   = 1;
    public const S_APPROVED = 2;
    public const S_TRANSFER = 3;
    public const S_NOTFOUND = 4;
    public const S_DISAPPROVED = 5;
}
