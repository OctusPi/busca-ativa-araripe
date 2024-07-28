<?php

namespace App\Http\Controllers;

use App\Models\Organ;
use App\Models\Student;
use App\Providers\Modules;
use App\Utils\Dates;
use Illuminate\Http\Request;
use App\Http\Middlewares\Data;

class Students extends Controller
{
    public function __construct(){
        parent::__construct(Student::class, Modules::M_STUDENTS);
    }

    public function list(Request $request)
    {
        if($request->birth){
            $request->birth = Dates::convert($request->birth, Dates::PTBR, Dates::UTC);
        }

        return $this->base_list($request, ['name', 'sige', 'cpf', 'mother', 'birth', 'status'], ['name']);
    }

    public function selects(Request $request)
    {
        return response()->json([
            'organs' => Data::find(Organ::class, order:['name']),
            'races'  => Student::list_races(),
            'sexs'   => Student::list_sexs(),
            'status' => Student::list_status()
        ]);
    }

    public function import(Request $request)
    {
        return null;
    }
}
