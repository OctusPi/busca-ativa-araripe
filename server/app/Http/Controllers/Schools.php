<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use App\Providers\Modules;

class Schools extends Controller
{
    public function __construct(){
        parent::__construct(School::class, Modules::M_SCHOOLS);
    }
}
