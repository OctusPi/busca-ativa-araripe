<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Grid;
use App\Models\Organ;
use App\Models\School;
use App\Models\Serie;
use App\Models\Subject;
use App\Models\Teacher;
use App\Providers\Modules;
use Illuminate\Http\Request;
use App\Http\Middlewares\Data;

class Grids extends Controller
{
    public function __construct(){
        parent::__construct(Grid::class, Modules::M_GRIDS);
    }

    public function list(Request $request)
    {
        return $this->base_list($request, ['organ', 'school', 'serie', 'classe', 'subject', 'teacher'], ['serie'], ['school','serie','classe','subject','teacher']);
    }

    public function selects(Request $request)
    {
        $selects = [
            'organs' => Data::find(Organ::class, order:['name']),
            'days'   => Grid::list_days()
        ];

        if($request->key == 'organs'){
            $selects['schools'] = Data::find(School::class, ['organ' => $request->search], ['name']);
            $selects['series']   = Data::find(Serie::class, ['organ' => $request->search], ['name']);
            $selects['subjects'] = Data::find(Subject::class, ['organ' => $request->search], ['name']);
            $selects['teachers'] = Data::find(Teacher::class, ['organ' => $request->search], ['name']);
        }

        if ($request->key == 'classes') {
            $search = explode(',', $request->search);

            $selects['schools'] = Data::find(School::class, ['organ' => $search[0]], ['name']);
            $selects['series']   = Data::find(Serie::class, ['organ' =>  $search[0]], ['name']);
            $selects['subjects'] = Data::find(Subject::class, ['organ' =>  $search[0]], ['name']);
            $selects['teachers'] = Data::find(Teacher::class, ['organ' =>  $search[0]], ['name']);

            $selects['classes']  = Data::find(Classe::class, [
                ['column'=>'organ', 'operator'=>'=',  'value'=>$search[0]],
                ['column'=>'school', 'operator'=>'=',  'value'=>$search[1]],
                ['column'=>'serie', 'operator'=>'=',  'value'=>$search[2]]
            ], ['name']);
        }

        return response()->json($selects);

    }
}
