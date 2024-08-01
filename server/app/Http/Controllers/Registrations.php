<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use App\Models\Classe;
use App\Models\School;
use App\Providers\Modules;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Middlewares\Data;
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

    public function selects(Request $request)
    {
        $selects = [
            'schools'  => Data::find(School::class, order: ['name']),
            'series'  => Data::find(Serie::class, order: ['name']),
            'status'  => Registration::list_status(),
        ];

        if($request->key == 'classe'){
            $search = explode(',', $request->search);
            $selects['classes'] = Data::find(Classe::class, [
                ['column' => 'school', 'operator' => '=', 'value' => $search[0]],
                ['column' => 'serie', 'operator' => '=', 'value' => $search[1]],
            ], ['name']);
        }

        return response()->json($selects);
    }
}
