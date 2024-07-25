<?php

namespace App\Http\Controllers;

use App\Models\Organ;
use App\Providers\Modules;
use Illuminate\Http\Request;

class Organs extends Controller
{
    public function __construct()
    {
        parent::__construct(Organ::class, Modules::M_ORGANS);
    }

    public function list(Request $request)
    {
        return $this->base_list($request, ['name', 'cnpj', 'status'], ['name']);
    }

    public function selects()
    {
        return response()->json([
            'status' => Organ::list_status()
        ]);
    }
}
