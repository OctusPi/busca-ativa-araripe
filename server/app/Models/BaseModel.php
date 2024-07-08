<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    // consts profile
    protected const P_ADMIN = 1;
    protected const P_MANAGER = 2;
    protected const P_TECHNICIAN = 3;
    protected const P_DIRECTOR = 4;
    protected const P_SECRETARY = 5;
    
    // consts modules
    protected const M_INITIAL = 0;
    protected const M_MANAGER = 1;
    protected const M_USERS = 3;
    protected const M_ORGANS = 4;
    protected const M_SCHOOLS = 5;
    protected const M_SERIES = 6;
    protected const M_CLASSES = 7;
    protected const M_SUBJECTS = 8;
    protected const M_STUDENTS = 9;
    protected const M_STUDENTS_REG = 10;
    protected const M_TEACHERS = 11;
    protected const M_GRIDS = 12;
    protected const M_FREQUENCIES = 13;

    // consts status value
    protected const S_INACTIVE = 0;
    protected const S_ACTIVE   = 1;
    protected const S_APPROVED = 2;
    protected const S_TRANSFER = 3;
    protected const S_NOTFOUND = 4;
    protected const S_DISAPPROVED = 5;
}
