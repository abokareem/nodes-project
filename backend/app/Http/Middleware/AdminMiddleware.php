<?php

namespace App\Http\Middleware;

use App\Exceptions\AdminAccessException;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws AdminAccessException
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            throw new AdminAccessException();
        }

        return $next($request);
    }
}
