<?php

declare(strict_types=1);

namespace App\Overrides\Pxlrbt\FilamentSpotlight;

use App\Overrides\Pxlrbt\FilamentSpotlight\Actions\RegisterPages;
use App\Overrides\Pxlrbt\FilamentSpotlight\Actions\RegisterResources;
use App\Overrides\Pxlrbt\FilamentSpotlight\Actions\RegisterUserMenu;
use Filament\Panel;
use Override;
use pxlrbt\FilamentSpotlight\SpotlightPlugin as BaseSpotlightPlugin;

final class SpotlightPlugin extends BaseSpotlightPlugin
{
    /**
     * I have to move all logic to App\Providers\Filament\Spotlight\SpotlightServiceProvider
     * because Laravel Octane flush callback listeners after request only
     * if they were in ServiceProvider
     */
    #[Override]
    public function boot(Panel $panel): void
    {
        //
    }

    /**
     * @param  Panel  $panel
     */
    #[Override]
    public static function registerNavigation($panel): void
    {
        RegisterPages::boot($panel);
        RegisterResources::boot($panel);
        RegisterUserMenu::boot($panel);
    }
}
