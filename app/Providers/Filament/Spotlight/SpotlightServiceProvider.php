<?php

declare(strict_types=1);

namespace App\Providers\Filament\Spotlight;

use App\Overrides\Pxlrbt\FilamentSpotlight\SpotlightPlugin;
use Filament\Events\TenantSet;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

final class SpotlightServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::serving(function (): void {
            Config::set('livewire-ui-spotlight.include_js', false);

            /** @var Panel */
            $panel = Filament::getCurrentPanel();

            if (Filament::hasTenancy()) {
                Event::listen(TenantSet::class, function () use ($panel): void {
                    SpotlightPlugin::registerNavigation($panel);
                });
            } else {
                SpotlightPlugin::registerNavigation($panel);
            }
        });
    }
}
