<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Dates;
use App\Utils\Notify;
use App\Security\Guardian;
use Illuminate\Http\Request;
use App\Mail\ChangePassRequest;
use Illuminate\Support\Facades\Log;
use App\Mail\ChangePassNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Authentication user by request with username and password
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if(!$request->username || !$request->password) {
            return response()->json(Notify::warning("Campos obrigatórios não informados..."), 401);
        }

        $user = User::where("username", $request->username)->first();
        
        if(!$user){
            return response()->json(Notify::warning("Usuário ou senha inválidos!"), 401);
        }

        if(!$user->status){
            return response()->json(Notify::warning("Usuário bloqueado pelo administrador!"), 403);
        }

        if(Auth::attempt($request->only('username', 'password'))){
            $user = $request->user();
            $user-> lastlogin = $user->nowlogin;
            $user-> nowlogin  = now();
            $user-> save();

            $abilities = array_column($user->modules, 'module');
            $token = $user->createToken('authToken', $abilities, now()->addHours(3));

            return response()->json([
                'notify' => ['type' => Notify::SUCCESS, 'msg' => 'Login realizado com sucesso, redirecionando...'],
                'token'  => $token->plainTextToken,
                'user'   => [
                    'name' => $user->name,
                    'profile' => User::list_profiles()[$user->profile] ?? '',
                    'lastlogin' => Dates::convert($user->lastlogin, Dates::UTC_TIME, Dates::PTBR_TIME),
                    'navigation' => $user->modules
                ]
            ]);
        }

        return response()->json(Notify::warning("Usuário ou senha inválidos!"), 401);

    }

    public function logout(Request $request)
    {
        $tokenData = Guardian::getPersonalToken($request->bearerToken());
        if($tokenData){
            $tokenData->delete();
            return response()->json(Notify::success("Saída realizada com segurança..."));
        }

    }

    public function recover(Request $request)
    {
        $user = User::where("username", $request->username)->first();
        
        if(!$user){
            return response()->json(Notify::warning("Usuário não localizado..."),404);
        }

        try {
            $token = $user->createToken("resetPassword", ['reset-password'], now()->addHour());
            $user->token = $token->plainTextToken;
            $user->passchange = true;

            if($user->save()){
                Mail::to($user)->send(new ChangePassRequest($user, Dates::futurePTBR(1)));
                return response()->json(Notify::success('E-mail para recuperação de senha enviado...'));
            }

            return response()->json(Notify::error('Falha ao solicitar recuperação de senha, contate o administrador!'), 500);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(Notify::warning('Falha ao processar dados!'), 500);
        }
    }

    public function validate_renew(Request $request)
    {
        $token = $request->token;
        
        if(!Guardian::checkToken($token)){
            return response()->json(Notify::warning('Link de recuperação expirou, solicite um novo...'), 404);
        }

        $user = Guardian::getUserByToken($token);

        if(!$user){
            return response()->json(Notify::warning('Token de recuperação inválido...'), 404);
        }

        return response()->json([
            'name' => $user->name,
            'profile' => User::list_profiles()[$user->profile] ?? '',
            'lastlogin' => Dates::convert($user->lastlogin, Dates::UTC_TIME, Dates::PTBR_TIME),
        ], 200);
    }

    public function renew(Request $request)
    {
        $rules = ['newpass' => 'required', 'confpass' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(Notify::warning('Campos Obrigatórios não informados!'), 400);
        }

        if ($request->newpass !== $request->confpass) {
            return response()->json(Notify::warning('Senhas divergentes informadas!'), 400);
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z]).{8,}$/', $request->newpass)) {
            return response()->json(Notify::warning('Senha não atende aos critérios de segurança! 
            Sua senha deve conter no minimo 08 caracteres com letras, números e símbolos'), 400);
        }

        $token = $request->token;
        $check = Guardian::checkToken($token);
        $user  = User::where('token', $token)->where('passchange', true)->first();

        if (!$check || !$user) {
            return response()->json(Notify::warning('Token inválido ou expirado!'), 401);
        }

        $user->setAttribute('password', Hash::make($request->newpass));
        $user->token = null;
        $user->passchange = false;

        if ($user->update()) {
            $user->tokens()->delete();
            Mail::to($user)->send(new ChangePassNotification());
            return response()->json(Notify::success('Senha alterada com sucesso!'), 200);
        }

        return response()->json(Notify::error('Falha ao cadastrar senha...'), 500);
    }

    public function check(Request $request)
    {
        $token = $request->bearerToken();
        return Guardian::checkToken($token)
        ? response()->json(['token_valid' => true], 200)
        : response()->json(Notify::info('Login expirado, realize o login novamente...'), 401);
    }
}
