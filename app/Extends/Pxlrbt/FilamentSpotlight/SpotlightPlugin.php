<?php

declare(strict_types=1);

namespace App\Extends\Pxlrbt\FilamentSpotlight;

use Filament\Panel;
use Filament\Events\TenantSet;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use App\Extends\Pxlrbt\FilamentSpotlight\Actions\RegisterResources;
use pxlrbt\FilamentSpotlight\SpotlightPlugin as BaseSpotlightPlugin;

final class SpotlightPlugin extends BaseSpotlightPlugin
{
    public function boot(Panel $panel): void
    {
        Filament::serving(function () use ($panel) {
            config()->set('livewire-ui-spotlight.include_js', false);
            if (Filament::hasTenancy()) {
                Event::listen(TenantSet::class, function () use ($panel) {
                    self::registerNavigation($panel);
                });
            } else {
                self::registerNavigation($panel);
            }
        });
    }

    public static function registerNavigation($panel)
    {
        parent::registerNavigation($panel);

        RegisterResources::boot($panel);
    }
}
