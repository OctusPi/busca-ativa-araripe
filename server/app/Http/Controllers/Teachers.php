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

    // public function save(Request $request)
    // {
    //     if ($request->id) {
    //         return parent::save($request);
    //     }

    //     $name = $request->name;
    //     $email = $request->email;
    //     $cpf = $request->cpf;
    //     $phone = $request->phone;
    //     $qualification = $request->qualification;

    //     $saveTeacher = $this->base_save($request, [
    //         'name'          => $name,
    //         'email'         => $email,
    //         'cpf'           => $cpf,
    //         'phone'         => $phone,
    //         'qualification' => $qualification
    //     ]);

    //     return $saveTeacher;
    // }

    // function list(Request $request)
    // {
    //     $search = ['name', 'email'];
    //     return $this->base_list($request, $search, ['name']);
    // }

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
