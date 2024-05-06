<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Pages\Dashboard;
use Illuminate\Support\Facades\Blade;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Support\Facades\FilamentView;
use App\Http\Middleware\Filament\EnsureEmailIsVerified;
use App\Overrides\Jeffgreco13\FilamentBreezy\BreezyCore;

final class WebPanelServiceProvider extends PanelServiceProvider
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
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/Web'), for: 'App\\Filament\\Widgets\\Web')
            ->widgets([
                FilamentInfoWidget::class,
            ])
            ->login()
            ->emailVerification()
            ->emailVerifiedMiddlewareName(
                EnsureEmailIsVerified::class
            )
            ->registration()
            ->passwordReset()
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: false,
                        shouldRegisterNavigation: false,
                        hasAvatars: false,
                        slug: 'profile'
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
