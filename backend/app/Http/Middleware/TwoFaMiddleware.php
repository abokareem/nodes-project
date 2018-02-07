<?php

namespace App\Http\Middleware;

use App\Exceptions\TwoFaInvalidCode;
use App\Services\TwoFaService;
use Closure;
use Illuminate\Support\Facades\Auth;

class TwoFaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws TwoFaInvalidCode
     */
    public function handle($request, Closure $next)
    {
        $google2FA = app(TwoFaService::class);
        dd(Auth::user());
        if ($google2FA->verifyCode(Auth::user()->google2fa_secret, $request->get('twofa'))) {
            return $next($request);
        }

        throw new TwoFaInvalidCode();
    }
}
