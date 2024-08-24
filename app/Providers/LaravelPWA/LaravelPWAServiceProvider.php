<?php

declare(strict_types=1);

namespace App\Providers\LaravelPWA;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LaravelPWA\Http\Controllers\LaravelPWAController;

final class LaravelPWAServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::get('/pwa-manifest.json', [LaravelPWAController::class, 'manifestJson'])
            ->name('laravelpwa.manifest');
    }
}
