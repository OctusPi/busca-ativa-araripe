<?php

namespace App\Http\Controllers;

use App\Providers\Modules;
use Illuminate\Http\Request;

class Home extends Controller
{
    public function __construct()
    {
        parent::__construct(null, Modules::M_INITIAL);
    }
}
