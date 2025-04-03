<?php

declare(strict_types=1);

namespace App\Providers\LaravelPWA;

use App\Http\Controllers\Api\PWA\Manifest\ManifestController;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class LaravelPWAServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $middleware = Filament::getCurrentPanel()?->getMiddleware();

        Route::middleware($middleware)->group(function (): void {
            Route::get('pwa-manifest.json', ManifestController::class)
                ->name('laravelpwa.manifest');

            Route::get('offline', \App\Filament\Pages\Web\Offline\OfflinePage::class)
                ->name('filament.web.pages.offline');
        });
    }
}
