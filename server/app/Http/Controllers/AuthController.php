<?php

namespace App\Http\Controllers;

use App\Utils\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt($request->only('username', 'password'))){
            return Response()->json(['token' => $request->user()->createToken('authorization')]);
        }

        return Response()->json(Notify::warning('Usuário ou Senha Inválidos!'), 403);
    }
}
