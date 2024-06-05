<?php

declare(strict_types=1);

namespace App\Providers\Filament\WebPanel;

use Filament\Panel;
use Illuminate\Support\Facades\Blade;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Support\Facades\FilamentView;
use App\Filament\Pages\Web\MyProfile\MyProfilePage;
use App\Providers\Filament\PanelServiceProvider;
use App\Overrides\Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Http\Middleware\Filament\EnsureEmailIsVerified\EnsureEmailIsVerifiedHandler;

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
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets/Web'), for: 'App\\Filament\\Widgets\\Web')
            ->widgets([
                FilamentInfoWidget::class,
            ])
            ->login()
            ->emailVerification()
            ->emailVerifiedMiddlewareName(
                EnsureEmailIsVerifiedHandler::class
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
                    ->customMyProfilePage(MyProfilePage::class)
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
            fn (): string => Blade::render('<x-web.topbar.login-button.login-button-component />')
        );
    }
}
