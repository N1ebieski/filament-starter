<?php

declare(strict_types=1);

namespace App\Providers\LaravelPWA;

use App\Http\Controllers\Api\PWA\Manifest\ManifestController;
use App\Providers\ServiceProvider;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

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
