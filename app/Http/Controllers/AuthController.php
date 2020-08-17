<?php

namespace App\Http\Controllers;

use App\Traits\Assets\DateUtil;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\AuthToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;

class AuthController extends BaseController
{
    public function __construct()
    {
        Config::set('auth.providers.users.model', User::class);
        Config::set('auth.providers.users.table', 'usr_users');
    }

    /*
     * author: Emanuel F.G. Leão
     * resume: a função, a partir do bearer token do
     * request, busca o usuário ao qual o token está
     * associado.
     */
    public function self(Request $request)
    {
        $token = $request->bearerToken();

        try {
            $authToken = AuthToken::query()->where('token', $token)->firstOrFail();
            $user = User::query()->findOrFail($authToken->user_id);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($user, 200);
    }

    /*
     * author: Emanuel F.G. Leão
     * resume: A função apaga o token informado do
     * banco
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        try {
            $authToken = AuthToken::query()->where('token', $token)->firstOrFail();
        } catch (\Exception $e) {
            return response()->json(['error' => 'invalid token'], 404);
        }

        $authToken->delete();

        JWTAuth::manager()->invalidate(new Token($authToken->token));

        return response()->json(['message' => 'user sucessful logout'], 200);
    }

    /*
     * author: Emanuel F.G. Leão
     * resume: A função login checa se as credencias são
     * validas. Caso seja um token é gerado e ocorre uma
     * tentativa de persistir no banco. Caso o usuário já
     * tenha um token registrado no banco será gerada uma
     * chave de confirmação. Com a chave de confirmação o
     * usuário pode confirmar que quer continuar com o
     * novo token.
     * OBS:
     * - Um token tem vida de 1h
     * - A chave para confirmar dura 2 minutos
     * - Quando uma chave é usada ela é excluida
     * - Ao fim do fluxo todas as chaves expiradas
     * são excluidas
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        $user = User::query()->where('email', $request->email)->firstOrFail();

        $dateExpire = DateUtil::now()->addHours(1);

        $authToken = new AuthToken();
        $authToken->token = $token;
        $authToken->user_id = $user->id;
        $authToken->dt_expire = $dateExpire;

        try {
            $authToken->save();
        } catch (\Exception $e){
            // Se não conseguir salvar o fluxo vem pra cá

            //busca o registro de auth_token
            $authToken = AuthToken::query()->where('user_id', $user->id)->firstOrFail();

            /*
             * se o token no banco já tiver expirado ele
             * sobrescreve pelo token gerado. Caso contratio
             * é gerada uma chave de confirmação com 2 min
             * de duração
             */
            if (DateUtil::isPast($authToken->dt_expire)){
                $authToken->token = $token;
                $authToken->dt_expire = $dateExpire;
                $authToken->update();
            }else{
                /*
                 * Checa se foi informada a chave de confirmação
                 * no momento do login.
                 * Caso sim: Verifica
                 * Caso não: Gera uma e informa
                 */
                if ($request->has('confirm_login_key')){
                    $query = DB::table('confirm_login')
                        ->where('key', $request->confirm_login_key);

                    User::clearOldConfirmLoginKeys();

                    $confirmKey = $query->first();

                    if ($confirmKey != null and DateUtil::isFuture(new Carbon($confirmKey->expire))){

                        $authToken->token = $token;
                        $authToken->dt_expire = $dateExpire;
                        $authToken->update();

                        $query->delete();
                    }else{
                        return response()->json(['error' => 'ivalid key'], 400);
                    }
                }else{
                    $key = Uuid::uuid4();
                    $expire = DateUtil::now()->addMinutes(2);
                    DB::table('confirm_login')->insert([
                        'key' => $key,
                        'expire' => $expire
                    ]);
                    return response()->json([
                        'message' => 'credentials are associated with active token',
                        'confirm_login_key' => $key
                    ], 403);
                }
            }
        }
        return $this->respondWithToken($token);
    }

    /*
     * author: Emanuel F.G. Leão
     * resume: a função recebe um request que deve ter
     * um token. Há três casos:
     * - Token Valido: Retorna o tempo restante do token
     * em segundos.
     * - Token Expirado: retorna 401
     * - Token Invalido: retorna 404
     */
    public function checkToken(Request $request)
    {
        $token = $request->bearerToken();

        try {
            $response = AuthToken::validateToken($token);
        } catch (\Exception $e){
            return response()->json(['error' => 'invalid token'], 404);
        }

        if ($response){
            return response()->json(['remaining_time' => $response], 200);
        }else{
            return response()->json(['error' => 'expired token'], 401);
        }

    }

    /*
     * author: Emanuel F.G Leão
     * resume: A função deve receber um request com
     * um campo token. É esperado que o token seja valido.
     * Se for um token valido ele é atualizado.
     */
    public function refreshToken(Request $request)
    {
        $token = $request->bearerToken();

        try {
            $token = AuthToken::refreshToken($token);
        } catch (\Exception $e){
            return response()->json(['error' => 'invalid token'], 404);
        }
        return $this->respondWithToken($token);
    }

    /*
     * Função padrão do JWT
     */
    private function respondWithToken($token, $expire = null)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }
}
