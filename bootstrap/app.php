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
            \App\Http\Middleware\TrustProxies\TrustProxiesMiddleware::class
        );
    })
    ->withProviders([
        \App\Providers\Config\ConfigServiceProvider::class,
        \App\Providers\App\AppServiceProvider::class,
        \App\Providers\Auth\AuthServiceProvider::class,
        \App\Providers\Event\EventServiceProvider::class,
        \App\Providers\Filament\Filament\FilamentServiceProvider::class,
        \App\Providers\Filament\WebPanel\WebPanelServiceProvider::class,
        \App\Providers\Filament\AdminPanel\AdminPanelServiceProvider::class,
        \App\Providers\Filament\UserPanel\UserPanelServiceProvider::class,
        \App\Providers\Filament\Spotlight\SpotlightServiceProvider::class,
        \App\Providers\Spotlight\SpotlightServiceProvider::class
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
