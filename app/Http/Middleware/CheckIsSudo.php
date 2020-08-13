<?php

namespace App\Http\Middleware;

use App\Models\AuthToken;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckIsSudo
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $isSudo = DB::table('auth_token')
            ->select('usr_users.is_sudo')
            ->where('token','=', $token)
            ->join('usr_users', 'usr_users.id', '=', 'auth_token.user_id' )
            ->first()->is_sudo;

        if ($isSudo == 0){
            return response()->json([
                'error' => 'this token does not have the propper permissions'
            ], 401);
        }

        return $next($request);
    }
}
