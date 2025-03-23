<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow all origins (use specific origins in production)
        header("Access-Control-Allow-Origin: *");

        // Allow specific HTTP methods
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

        // Allow specific headers (or allow all headers)
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN");

        // Allow credentials (set to true only if needed)
        header("Access-Control-Allow-Credentials: true");

        // Handle OPTIONS request
        if ($request->getMethod() == "OPTIONS") {
            return response()->json([], 200);
        }

        return $next($request);
    }
}
