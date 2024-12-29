<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->as('api.')
                ->group(function () {
                    $filenames = File::allFiles(base_path('routes').'/api');

                    foreach ($filenames as $filename) {
                        if ($filename->getExtension() !== 'php') {
                            continue;
                        }

                        require $filename;
                    }
                });
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->replace(
            \Illuminate\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\TrustProxies\TrustProxiesMiddleware::class
        );
    })
    ->withProviders([
        \App\Providers\Config\ConfigServiceProvider::class,
        \App\Providers\Mixin\MixinServiceProvider::class,
        \App\Providers\App\AppServiceProvider::class,
        \App\Providers\Auth\AuthServiceProvider::class,
        \App\Providers\Event\EventServiceProvider::class,
        \App\Providers\Filament\Filament\FilamentServiceProvider::class,
        \App\Providers\Filament\WebPanel\WebPanelServiceProvider::class,
        \App\Providers\Filament\AdminPanel\AdminPanelServiceProvider::class,
        \App\Providers\Filament\UserPanel\UserPanelServiceProvider::class,
        \App\Providers\Filament\Spotlight\SpotlightServiceProvider::class,
        \App\Providers\Spotlight\SpotlightServiceProvider::class,
        \App\Providers\LaravelPWA\LaravelPWAServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e) {
            if (! Config::get('app.debug') && $e->getPrevious() instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                App::abort(HttpResponse::HTTP_NOT_FOUND);
            }
        });
    })->create();
