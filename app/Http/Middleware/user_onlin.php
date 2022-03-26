<?php

namespace App\Http\Middleware;

use App\Models\personal_access_token;
use Closure;
use Illuminate\Http\Request;
use Cache;
use App\Models\User;
use Carbon\Carbon;
// use Facade\FlareClient\Http\Client;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class user_onlin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

          $user = JWTAuth::parseToken()->authenticate()  ;
            $expiresAt = now()->addMinutes(2); /* keep online for 2 min */
            Cache::put( $user->id, true, $expiresAt);


        return $next($request);
    }
}

