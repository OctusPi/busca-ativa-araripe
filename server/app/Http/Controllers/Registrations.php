<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use App\Providers\Modules;
use Illuminate\Support\Facades\Validator;

class Registrations extends Controller
{
    public function __construct(){
        parent::__construct(Registration::class, Modules::M_REGS);
    }

    public function validate_reg(?array $data):bool
    {
        return $this->validate($data) == null;
    }
}
