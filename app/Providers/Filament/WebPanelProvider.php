<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\User\User;
use Filament\Facades\Filament;
use App\Filament\Http\Middleware;
use Filament\Navigation\MenuItem;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Blade;
use Filament\Support\Facades\FilamentView;
use App\Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;
use App\Extends\Jeffgreco13\FilamentBreezy\BreezyCore;

class WebPanelProvider extends PanelProvider
{
    public const ID = 'web';

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->default()
            ->id(self::ID)
            ->path('')
            ->homeUrl('/')
            ->discoverResources(in: app_path('Filament/Resources/Web'), for: 'App\\Filament\\Resources\\Web')
            ->discoverPages(in: app_path('Filament/Pages/Web'), for: 'App\\Filament\\Pages\\Web')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/Web'), for: 'App\\Filament\\Widgets\\Web')
            ->widgets([
                Widgets\FilamentInfoWidget::class,
            ])
            ->login()
            ->emailVerification()
            ->emailVerifiedMiddlewareName(
                Middleware\EnsureEmailIsVerified::class
            )
            ->registration()
            ->passwordReset()
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        condition: false,
                        shouldRegisterUserMenu: false,
                        shouldRegisterNavigation: false,
                        slug: 'profile',
                    )
                    ->enableTwoFactorAuthentication()
            ])
            ->spa();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::topbar.end',
            fn (): string => Blade::render('<x-web.topbar.login-button />')
        );
    }
}
