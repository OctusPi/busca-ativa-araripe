<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Providers\Modules;
use Illuminate\Http\Request;

class Classes extends Controller
{
    public function __construct(){
        parent::__construct(Classe::class, Modules::M_CLASSES);
    }
}
