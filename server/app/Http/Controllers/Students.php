<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Providers\Modules;

class Students extends Controller
{
    public function __construct(){
        parent::__construct(Student::class, Modules::M_STUDENTS);
    }
}
