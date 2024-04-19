<?php

declare(strict_types=1);

namespace App\Overrides\Pxlrbt\FilamentSpotlight;

use Override;
use Filament\Panel;
use Filament\Events\TenantSet;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use pxlrbt\FilamentSpotlight\Actions\RegisterUserMenu;
use App\Overrides\Pxlrbt\FilamentSpotlight\Actions\RegisterPages;
use pxlrbt\FilamentSpotlight\SpotlightPlugin as BaseSpotlightPlugin;
use App\Overrides\Pxlrbt\FilamentSpotlight\Actions\RegisterResources;

final class SpotlightPlugin extends BaseSpotlightPlugin
{
    #[Override]
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

    #[Override]
    public static function registerNavigation($panel)
    {
        RegisterPages::boot($panel);
        RegisterResources::boot($panel);
        RegisterUserMenu::boot($panel);
    }
}
