<?php

namespace App\Http\Controllers;

use App\Models\Organ;
use App\Models\Serie;
use App\Providers\Modules;
use Illuminate\Http\Request;
use App\Http\Middlewares\Data;

class Series extends Controller
{
    public function __construct(){
        parent::__construct(Serie::class, Modules::M_SERIES);
    }

    public function list(Request $request)
    {
        return $this->base_list($request, ['name', 'code', 'level']);
    }

    public function selects(Request $request)
    {
        return response()->json(
            [
                'organs' => Data::find(Organ::class, order:['name']),
                'courses' => Serie::list_courses(),
                'levels' => Serie::list_levels(),
                'status' => Serie::list_status()
            ]
        );
    }
}
