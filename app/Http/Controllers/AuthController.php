<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\AuthToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use mysql_xdevapi\Exception;

class AuthController extends BaseController
{
    public function __construct()
    {
        Config::set('auth.providers.users.model', User::class);
        Config::set('auth.providers.users.table', 'usr_users');
    }

    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        $user = User::query()->where('email', $request->email)->firstOrFail();
        $dateExpire = (new \DateTime('America/Sao_Paulo'))->add(new \DateInterval('PT1H'));

        $authToken = new AuthToken();
        $authToken->token = $token;
        $authToken->user_id = $user->id;
        $authToken->dt_expire = $dateExpire;

        try {
            $authToken->save();
        } catch (\Exception $e){
            $authToken = AuthToken::query()->firstOrFail('user_id', $user->id);


            if ($request->query->has('confirmlogin')){

                $authToken->token = $token;
                $authToken->dt_expire = $dateExpire;
                $authToken->update();
            }else{
                return response()->json(['error'=>'user already has a valid token'], 403);
            }
        }

        return $this->respondWithToken($token);
    }

    public function checkToken(Request $request)
    {
        try {
            $response = AuthToken::validateToken($request->token);
        } catch (\Exception $e){
            return response()->json(['error' => 'invalid token'], 404);
        }

        if ($response){
            return response()->json(['remaining_time' => $response], 200);
        }else{
            return response()->json(['error' => 'expired token'], 401);
        }

    }

    public function refreshToken(Request $request)
    {
        try {
            $token = AuthToken::refreshToken($request->token);
        } catch (\Exception $e){

            return response()->json(['error' => 'invalid token'], 404);
        }
        return $this->respondWithToken($token);
    }

    private function respondWithToken($token, $expire = null)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }
}
