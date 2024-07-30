<?php

namespace App\Http\Controllers;

use App\Models\Subject;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Providers\Modules;
use App\Http\Middlewares\Data;
use App\Models\Organ;



class Subjects extends Controller
{
    public function __construct(){
        parent::__construct(Subject::class, Modules::M_SUBJECTS);
    }
        
    public function list(Request $request)
    {
        return $this->base_list($request, ['name', 'description'], ['name']);
    }
    
    public function selects(Request $request)
    {
        return response()->json(
            [
                'organs' => Data::find(Organ::class, order: ['name']),
                'areas' => Subject::list_areas()
            ]
        );
    }
}
