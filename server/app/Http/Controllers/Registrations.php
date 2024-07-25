<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use App\Providers\Modules;

class Registrations extends Controller
{
    public function __construct(){
        parent::__construct(Registration::class, Modules::M_REGS);
    }
}
