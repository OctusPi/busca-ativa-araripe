<?php

namespace App\Http\Controllers;

use App\Models\Organ;
use App\Models\Serie;
use App\Models\Classe;
use App\Models\School;
use App\Providers\Modules;
use Illuminate\Http\Request;
use App\Http\Middlewares\Data;

class Classes extends Controller
{
    public function __construct(){
        parent::__construct(Classe::class, Modules::M_CLASSES);
    }

    public function list(Request $request)
    {

        return $this->base_list($request, ['organ', 'school', 'serie'], ['name'], ['organ', 'school', 'serie']);
    }

    public function selects(Request $request)
    {
        $selects = [
            'organs'  => Data::find(Organ::class, order: ['name']),
            'turns'   => Classe::list_turns(),
            'courses' => Serie::list_courses(),
            'status'  => Classe::list_status()
        ];

        if($request->key == 'school'){
            $selects['schools'] = Data::find(School::class, ['organ' => $request->search], ['name']);
            $selects['series'] = Data::find(Serie::class, ['organ' => $request->search], order: ['name']);
        }

        return response()->json($selects);
    }
}
