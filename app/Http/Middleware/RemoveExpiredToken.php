<?php

namespace App\Http\Middleware;

use App\Models\AuthToken;
use Closure;

class RemoveExpiredToken
{
    public function handle($request, Closure $next)
    {
        AuthToken::removeExpired();

        return $next($request);
    }
}
