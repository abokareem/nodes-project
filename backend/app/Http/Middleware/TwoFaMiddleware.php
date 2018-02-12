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
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->two_fa) {

                $google2FA = app(TwoFaService::class);

                if (is_null($request->get('twofa'))){
                    throw new TwoFaInvalidCode();
                }

                if ($google2FA->verifyCode(Auth::user()->google2fa_secret, $request->get('twofa'))) {

                    return $next($request);
                }

                throw new TwoFaInvalidCode();
            }
        }
        return $next($request);
    }
}
