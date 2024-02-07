<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Lang;
use Illuminate\Session\Middleware\StartSession;
use Filament\PanelProvider as BasePanelProvider;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use App\Extends\Pxlrbt\FilamentSpotlight\SpotlightPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

abstract class PanelProvider extends BasePanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->colors([
                'primary' => Color::Blue,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->url(fn (): string => Filament::getPanel('web')->getHomeUrl())
                    ->label(Lang::get('home.pages.index.title'))
                    ->icon('heroicon-s-home'),
                MenuItem::make()
                    ->url(fn (): string => Filament::getPanel('user')->getHomeUrl())
                    ->visible(fn (Panel $panel): bool => $panel->auth()->check())
                    ->label(Lang::get('user.pages.panel.title'))
                    ->icon('heroicon-s-user'),
                MenuItem::make()
                    ->url(fn (): string => Filament::getPanel('admin')->getHomeUrl())
                    ->visible(fn (Panel $panel): bool => $panel->auth()->user()?->can('admin.access'))
                    ->label(Lang::get('admin.pages.panel.title'))
                    ->icon('heroicon-m-shield-exclamation'),
            ])
            ->authGuard('web')
            ->globalSearch(false)
            ->plugins([
                SpotlightPlugin::make(),
            ])
            ->bootUsing(function (Panel $panel): void {
                $panel->userMenuItems([
                    'account' => MenuItem::make()->url(function (Panel $panel): string {
                        if ($panel->auth()->check()) {
                            $panel = Filament::getDefaultPanel()->getId();

                            return MyProfilePage::getUrl(panel: $panel);
                        }

                        return '';
                    })->label(Lang::get('filament-breezy::default.profile.my_profile'))
                ]);
            });
    }
}
