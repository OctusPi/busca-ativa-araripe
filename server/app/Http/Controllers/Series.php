<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use Illuminate\Http\Request;
use App\Providers\Modules;

class Series extends Controller
{
    public function __construct(){
        parent::__construct(Serie::class, Modules::M_SERIES);
    }
}
