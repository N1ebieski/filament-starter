<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Models\User\User;
use App\Overrides\Pxlrbt\FilamentSpotlight\SpotlightPlugin;
use Filament\Facades\Filament;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider as BasePanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\View\View as ContractsView;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;

abstract class PanelServiceProvider extends BasePanelProvider
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
                    ->url(fn (): string => Filament::getPanel('web')->getHomeUrl()) // @phpstan-ignore-line
                    ->label(Lang::get('home.pages.index.title'))
                    ->icon('heroicon-s-home'),
                MenuItem::make()
                    ->url(fn (): string => Filament::getPanel('user')->getHomeUrl()) // @phpstan-ignore-line
                    ->visible(fn (Panel $panel): bool => $panel->auth()->check())
                    ->label(Lang::get('user.pages.panel.title'))
                    ->icon('heroicon-s-user'),
                MenuItem::make()
                    ->url(fn (): string => Filament::getPanel('admin')->getHomeUrl()) // @phpstan-ignore-line
                    ->visible(function (Panel $panel): bool {
                        /** @var User|null */
                        $user = $panel->auth()->user();

                        return $user?->can('admin.access') ?? false;
                    })
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
                    })->label(Lang::get('filament-breezy::default.profile.my_profile')),
                ]);
            })
            ->brandLogo(fn (): ContractsView => View::make('filament.logo.logo'))
            ->favicon(asset('favicon.ico'));
    }
}
