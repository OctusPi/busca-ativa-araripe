<?php

namespace App\Http\Controllers;

use App\Security\Common;
use Illuminate\Http\Request;

class Frequencies extends Controller
{
    public function __construct(){
        parent::__construct(true, [Common::M_FREQUENCIES['module']]);
    }
}
