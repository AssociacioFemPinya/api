<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    public const EXCLUDE_ENVIRONMENTS = [
        'local',
        'development',
        'dev',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (in_array(config('app.env'), self::EXCLUDE_ENVIRONMENTS)) {
            return $next($request);
        }

        $apiKey = config('services.api_key');

        $apiKeyIsValid = (
            ! empty($apiKey)
            && $request->header('x-api-key') == $apiKey
        );

        abort_if(! $apiKeyIsValid, 403, 'Access denied');

        return $next($request);
    }
}
