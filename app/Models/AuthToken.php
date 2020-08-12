<?php


namespace App\Models;

use App\Traits\Assets\DateUtil;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthToken extends Model
{
    protected $primaryKey = "user_id";

    protected $table = 'auth_token';

    protected $fillable = ['token', 'dt_expire', 'user_id'];

    protected $dates = ['dt_expire'];

    public $timestamps = false;

    public function user ()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /*
     * author: Emanuel F.G. Leão
     * resume: A função recebe um token.
     * Se o token não estiver no banco é lançada
     * uma exceção. Caso o token esteja, é checado
     * se o token expirou ou não. Caso o token
     * não tenha expirado um novo token é gerado.
     */
    static public function refreshToken($token)
    {
        /** @var JWTAuth */

        $now = DateUtil::now();

        try{
            $token = AuthToken::query()->where('token', $token)->firstOrFail();
        }catch (\Exception $e){
            throw $e;
        }
        $user = $token->user()->first();
        $newToken = JWTAuth::fromUser($user);

        if (DateUtil::isFuture($token->dt_expire)){
            $token->token = $newToken;
            $token->dt_expire = $now->addHours(1);
            $token->update();
        }else{
            $token->delete();
            return false;
        }

        return $token->token;
    }

    /*
     * author: Emanuel F.G. Leão
     * resume: A função checa o token.
     * Se ele não existir é gerada uma
     * exceção. Se ele estiver expirado
     * é retornado false e caso ele
     * seja valido é retornado o tempo
     * restante (de sua vida util) em
     * segundo
     */
    static public function validateToken ($token){
        $now = DateUtil::now();

        try{
            $token = AuthToken::query()->where('token', $token)->firstOrFail();
        }catch (\Exception $e){
            throw $e;
        }

        //Se o token expirou
        if (DateUtil::isPast($token->dt_expire)){
            $token->delete();
            return false;
        }else{
            $expireInSeconds = $token->dt_expire->getTimestamp();
            $nowInSeconds = $now->getTimestamp();
            return $expireInSeconds - $nowInSeconds;
        }
    }
}
