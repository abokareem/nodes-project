<?php

namespace App\Http\Middleware;

use Closure;
use PragmaRX\Google2FA\Google2FA;

class TwoFaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $google2FA = app(Google2FA::class);



        return $next($request);
    }
}
