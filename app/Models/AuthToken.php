<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use function Illuminate\Support\Facades\Date;

class AuthToken extends Model
{
    protected $primaryKey = 'token';

    protected $table = 'auth_token';

    protected $fillable = ['token', 'dt_expire', 'user_id'];

    protected $dates = ['dt_expire'];

    public $timestamps = false;

    static public function refreshToken($token)
    {
        //Diferença do fuso horário
        $braziliaUTC = -3;
        //Hora atual com o fuso horário
        $now = Carbon::now()->addHours($braziliaUTC);

        try{
            $token = AuthToken::query()->findOrFail($token);
        }catch (\Exception $e){
            throw $e;
        }

        if ($now < $token->dt_expire){
            $token->dt_expire = $now->addHours(1);
            $token->update();
        }else{
            $token->delete();
            return false;
        }

        return $token->token;
    }

    static public function validateToken ($token){
        //Diferença do fuso horário
        $braziliaUTC = -3;
        //Hora atual com o fuso horário
        $now = Carbon::now()->addHours($braziliaUTC);

        try{
            $token = AuthToken::query()->findOrFail($token);
        }catch (\Exception $e){
            throw $e;
        }

        //Se o token expirou
        if ($now > $token->dt_expire){
            $token->delete();
            return false;
        }else{
            $expireInSeconds = $token->dt_expire->getTimestamp();
            $nowInSeconds = $now->getTimestamp();
            return $expireInSeconds - $nowInSeconds;
        }
    }
}
