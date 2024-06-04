<?php

declare(strict_types=1);

namespace App\Providers\Filament\Spotlight;

use Filament\Events\TenantSet;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use App\Overrides\Pxlrbt\FilamentSpotlight\SpotlightPlugin;

final class SpotlightServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::serving(function () {
            Config::set('livewire-ui-spotlight.include_js', false);

            $panel = Filament::getCurrentPanel();

            if (Filament::hasTenancy()) {
                Event::listen(TenantSet::class, function () use ($panel) {
                    SpotlightPlugin::registerNavigation($panel);
                });
            } else {
                SpotlightPlugin::registerNavigation($panel);
            }
        });
    }
}
