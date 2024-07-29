<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Providers\Modules;

class Teachers extends Controller
{
    public function __construct(){
        parent::__construct(Teacher::class, Modules::M_TEACHERS);
    }

    public function selects(Request $request)
    {
        return response()->json(
            [                
                'qualifications' => Teacher::list_qualifications()
            ]
        );
    }
}
