<?php

use App\Helper\MainHelper;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except:[
            '/api/auth/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // if ($exceptions instanceof ValidationException) {
        //     $data = (new MainHelper())->buildResponse(null, 400, $exceptions?->errors()?? $exceptions->getMessage());
        //     return response()->json($data, $data['status']);
        // }
    })->create();
