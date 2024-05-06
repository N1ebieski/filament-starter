<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->replace(
            \Illuminate\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\TrustProxies::class
        );
    })
    ->withProviders([
        \App\Providers\ConfigServiceProvider::class,
        \App\Providers\AppServiceProvider::class,
        \App\Providers\AuthServiceProvider::class,
        \App\Providers\EventServiceProvider::class,
        \App\Providers\Filament\FilamentServiceProvider::class,
        \App\Providers\Filament\WebPanelServiceProvider::class,
        \App\Providers\Filament\AdminPanelServiceProvider::class,
        \App\Providers\Filament\UserPanelServiceProvider::class,
        \App\Providers\Filament\SpotlightServiceProvider::class,
        \App\Providers\SpotlightServiceProvider::class
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
