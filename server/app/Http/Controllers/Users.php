<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Models\Organ;
use App\Mail\Wellcome;
use App\Providers\Modules;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Middlewares\Data;
use Illuminate\Support\Facades\Mail;

class Users extends Controller
{
    public function __construct()
    {
        parent::__construct(User::class, Modules::M_USERS);
    }

    public function save(Request $request)
    {
        if($request->id){
            return parent::save($request);
        }

        $passwd = Str::random(8);
        $username = $request->email;
        $password = $passwd;

        $saveUser = $this->base_save($request, [
            'username'   => $username,
            'password'   => $password,
            'passchange' => true
        ]);

        if($saveUser->status() == 200){
            Mail::to($this->model)->send(new Wellcome($this->model, $passwd));
        }

        return $saveUser;
    }

    function list(Request $request){
        $search = ['name', 'email', 'perfil'];
        return $this->base_list($request, $search, [ 'name']);
    }

    public function selects(Request $request)
    {

        $selects = [
            'profiles' => User::list_profiles(),
            'modules' => User::list_modules(),
            'status'  => User::list_status(),
            'organs' => Data::find(Organ::class, order:['name']),
            'schools' => Data::find(School::class, order:['name'])
        ];

        if($request->key == 'schools'){
            $search = array_column(json_decode($request->serch, true), 'id');
            $search_params = [];

            if(is_array($search)){
                foreach($search as $id){
                    $search_params[] = ['column' => 'organ', 'operator' => '=', 'value' => $id];
                }

                $selects['schools'] = Data::find(School::class, $search_params, ['name']);
            }

        }

        return response()->json($selects);
    }
}
