<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    // consts profile
    public const P_ADMIN = 1;
    public const P_MANAGER = 2;
    public const P_TECHNICIAN = 3;
    public const P_DIRECTOR = 4;
    public const P_SECRETARY = 5;
    
    // consts modules
    public const M_INITIAL = 0;
    public const M_MANAGER = 1;
    public const M_USERS = 3;
    public const M_ORGANS = 4;
    public const M_SCHOOLS = 5;
    public const M_SERIES = 6;
    public const M_CLASSES = 7;
    public const M_SUBJECTS = 8;
    public const M_STUDENTS = 9;
    public const M_STUDENTS_REG = 10;
    public const M_TEACHERS = 11;
    public const M_GRIDS = 12;
    public const M_FREQUENCIES = 13;

    // consts status value
    public const S_INACTIVE = 0;
    public const S_ACTIVE   = 1;
    public const S_APPROVED = 2;
    public const S_TRANSFER = 3;
    public const S_NOTFOUND = 4;
    public const S_DISAPPROVED = 5;

    public static function validateFields(?int $id = null): array
    {
        return [];
    }

    public static function validateMsg():array
    {
        return [];
    }
}
