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
        using: function (): void {
            Route::middleware('api')
                ->prefix('api')
                ->as('api.')
                ->group(function (): void {
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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->replace(
            \Illuminate\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\TrustProxies\TrustProxiesMiddleware::class
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e): void {
            if (
                ! Config::get('app.debug')
                && $e->getPrevious() instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
            ) {
                App::abort(HttpResponse::HTTP_NOT_FOUND);
            }
        });
    })->create();
