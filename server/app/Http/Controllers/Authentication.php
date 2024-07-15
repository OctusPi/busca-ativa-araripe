<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Notify;
use App\Security\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class Authentication extends Controller
{
    /**
     * Authentication sanctum laravel
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('username', 'password'))) {
            $user = $request->user();
            $user->update(['lastlogin' => $user->nowlogin, 'nowlogin' => now()]);
            $abilities = $user->profile == Common::P_ADMIN ? ["*"] : array_column($user->modules, 'module');
            return Response()->json([
                'token' => $user->createToken('authorization', $abilities, now()->addDay())->plainTextToken,
                'navigation' => $user->modules,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile' => $user->profile,
                    'last_login' => $user->lastlogin
                ]
            ]);
        }

        return Response()->json(Notify::warning('Usuário ou Senha Inválidos!'), 403);
    }

    /**
     * Logout api remove authorization token
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->tokens()->delete();
            return Response()->json(Notify::success('Saida realizada com sucesso...'));
        } catch (\Throwable $th) {
            Log::error('Falha ao realizar saída: ' . $th->getMessage());
            return Response()->json(Notify::success('Falha ao realizar logout...'), 200);
        }
    }

    /**
     * Check if authorization token is active
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function active(Request $request)
    {
        $accessToken = PersonalAccessToken::findToken(str_replace('Bearer ', '', $request->server('HTTP_AUTHORIZATION')));

        if (!$accessToken) {
            return Response()->json(Notify::info('Login expirou, realize o login novamente!'), 403);
        }

        return Response()->json(Notify::success('Acesso Autorizado, Seja bem-vindo!'), 200);
    }

    /**
     * Password recovey request by email
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function recover(Request $request)
    {
        $user = User::where(['email', $request->email])->first();
        if ($user) {
            //send email and create recover token
            $token = $user->createToken('auth:recoverpass', ["recoverpass"], now()->addHour());
            return Response()->json(Notify::success('E-mail de recuperação de senha enviado...'));
        }

        return Response()->json(Notify::warning('Usuário não localizado...'), 404);
    }

    /**
     * Register a new password for user
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function renew(Request $request)
    {
        $accessToken = PersonalAccessToken::findToken($request->server('HTTP_RECOVER'));

        if (!$accessToken || $accessToken->expired()) {
            return Response()->json(Notify::info('Link expirou, solicite uma nova recuperação de senha!'), 401);
        }

        $user = User::find($accessToken->tokenable_id);

        if($user){
            //send mail information change password
            $user->update(['password' => $request->password]);
            return Response()->json(Notify::success('Senha alterada com sucesso...'));
        }

        return Response()->json(Notify::error('Falha ao cadastrar nova senha...'), 500);
    }
}
