<?php

declare(strict_types=1);

namespace App\Overrides\Pxlrbt\FilamentSpotlight;

use Override;
use Filament\Panel;
use pxlrbt\FilamentSpotlight\Actions\RegisterUserMenu;
use App\Overrides\Pxlrbt\FilamentSpotlight\Actions\RegisterPages;
use pxlrbt\FilamentSpotlight\SpotlightPlugin as BaseSpotlightPlugin;
use App\Overrides\Pxlrbt\FilamentSpotlight\Actions\RegisterResources;

final class SpotlightPlugin extends BaseSpotlightPlugin
{
    #[Override]
    /**
     * I have to move all logic to App\Providers\Filament\SpotlightServiceProvider
     * because Laravel Octane flush callback listeners after request only
     * if they were in ServiceProvider
     */
    public function boot(Panel $panel): void
    {
        //
    }

    #[Override]
    public static function registerNavigation($panel)
    {
        RegisterPages::boot($panel);
        RegisterResources::boot($panel);
        RegisterUserMenu::boot($panel);
    }
}
