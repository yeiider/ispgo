<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'pagebuilder/assets/*',
            'pagebuilder/uploads/*',
            '/admin/*',
            '/wiivo/api/handleWebhook',
            '/payment/handlewompievent'
        ])->alias([])->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
        $middleware->api();

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
