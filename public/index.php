<?php

use Illuminate\Http\Request;

try {

    define('LARAVEL_START', microtime(true));

    // Determine if the application is in maintenance mode...
    if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
        require $maintenance;
    }

    // Register the Composer autoloader...
    require __DIR__.'/../vendor/autoload.php';

    // Bootstrap Laravel and handle the request...
    (require_once __DIR__.'/../bootstrap/app.php')
        ->handleRequest(Request::capture());

} catch (Throwable $e) {
    file_put_contents(__DIR__.'/../storage/logs/critical_error.log', $e->getMessage() . "\n" . $e->getTraceAsString());
    http_response_code(500);
    echo "A critical error occurred. Check storage/logs/critical_error.log";
}
