<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'api/login',
            'api/register',
            'api/*',
         //   'http://192.168.20.50:8050/api/login',
         //   'http://192.168.20.50:8050/api/register',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
