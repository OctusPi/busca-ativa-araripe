<?php

namespace App\Http\Controllers;

use App\Models\Frequencie;
use App\Providers\Modules;
use Illuminate\Http\Request;

class Frequencies extends Controller
{
    public function __construct(){
        parent::__construct(Frequencie::class, Modules::M_FREQ);
    }
}
