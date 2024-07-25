<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\Modules;

class Reports extends Controller
{
    public function __construct(){
        parent::__construct(null, Modules::M_REPORTS);
    }
}
