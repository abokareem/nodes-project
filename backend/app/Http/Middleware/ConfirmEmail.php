<?php

namespace App\Http\Middleware;

use App\Exceptions\UnconfirmedEmailException;
use Closure;
use Illuminate\Support\Facades\Auth;

class ConfirmEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @throws UnconfirmedEmailException
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user->email_confirmed) {
            throw new UnconfirmedEmailException();
        }

        return $next($request);
    }
}
