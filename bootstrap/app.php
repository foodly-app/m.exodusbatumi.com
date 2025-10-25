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
            Route::middleware('web')->group(base_path('routes/mobile.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register mobile authentication middleware
        $middleware->alias([
            'mobile.auth' => \App\Http\Middleware\MobileAuth::class,
            'mobile.guest' => \App\Http\Middleware\MobileGuest::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
