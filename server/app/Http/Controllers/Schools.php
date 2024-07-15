<?php

namespace App\Http\Controllers;

use App\Security\Common;
use Illuminate\Http\Request;

class Schools extends Controller
{
    public function __construct(){
        parent::__construct(true, [Common::M_SCHOOLS['module']]);
    }
}
