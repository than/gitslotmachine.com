<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // API subdomain
            Route::middleware('api')
                ->domain('api.'.config('app.domain'))
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Badge subdomain
            Route::middleware('web')
                ->domain('badge.'.config('app.domain'))
                ->group(base_path('routes/badge.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
