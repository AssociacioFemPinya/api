<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        Log::info('RedirectIfAuthenticated');
        Log::info($guards);
        foreach ($guards as $guard) {
            Log::info('Guardcheck');

            if (Auth::guard($guard)->check()) {
                Log::info('check');

                Log::info('Redirecting to: ' . config('fortify.home'));
                return redirect(config('fortify.home'));
            }
        }

        return $next($request);
    }
}