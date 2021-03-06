<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class CheckIsSudoOrHirer
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        $query = DB::table('auth_token')
            ->select(['usr_users.is_sudo', 'usr_users.is_hirer'])
            ->where('token','=', $token)
            ->join('usr_users', 'usr_users.id', '=', 'auth_token.user_id' )
            ->first();

        $isSudo = $query->is_sudo;
        $isHirer = $query->is_hirer;

        if ($isSudo == 0 and $isHirer == 0){
            return response()->json([
                'error' => 'this token does not have the propper permissions'
            ], 401);
        }

        return $next($request);
    }
}
