<?php

namespace App\Http\Controllers;

use App\Models\Organ;
use App\Models\School;
use App\Providers\Modules;
use Illuminate\Http\Request;
use App\Http\Middlewares\Data;

class Schools extends Controller
{
    public function __construct(){
        parent::__construct(School::class, Modules::M_SCHOOLS);
    }

    public function list(Request $request)
    {
        return $this->base_list($request, ['name', 'inep', 'status'], ['name']);
    }

    public function selects(Request $request)
    {
        return response()->json(
            [
                'organs' => Data::find(Organ::class, order:['name']),
                'status' => School::list_status()
            ]
        );
    }
}
