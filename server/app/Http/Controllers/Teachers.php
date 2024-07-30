<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Providers\Modules;
use App\Http\Middlewares\Data;
use App\Models\Organ;

class Teachers extends Controller
{
    public function __construct()
    {
        parent::__construct(Teacher::class, Modules::M_TEACHERS);
    }

    public function list(Request $request)
    {
        return $this->base_list($request, ['name'], ['name']);
    }

    public function selects(Request $request)
    {
        return response()->json(
            [
                'qualifications' => Teacher::list_qualifications(),
                'organs'         => Data::find(Organ::class, order: ['name']),
            ]
        );
    }
}
