<?php

namespace App\Http\Middleware;

use App\Models\AuxTables\RegKeys;
use App\Traits\Assets\DateUtil;
use Closure;
use Illuminate\Support\Facades\DB;

class CheckRegKey
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key = $request->regKey;
        try {
            $regKey = RegKeys::query()->where('reg_key', $key)->firstOrFail();
        } catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()],400);
        }

        RegKeys::removeExpired();

        if (DateUtil::isPast($regKey->dt_expire)){
            return response()->json(['error' => 'invalid regKey'], 400);
        }

        return $next($request);
    }
}
