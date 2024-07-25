<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Providers\Modules;

class Subjects extends Controller
{
    public function __construct(){
        parent::__construct(Subject::class, Modules::M_SUBJECTS);
    }
}
