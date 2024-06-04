<?php

declare(strict_types=1);

namespace App\Providers\Filament\AdminPanel;

use Filament\Panel;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Blade;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Support\Facades\FilamentView;
use App\Providers\Filament\PanelServiceProvider;
use App\Overrides\Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Http\Middleware\Filament\VerifyEmail\VerifyEmailHandler;
use App\Http\Middleware\Filament\Authenticate\AuthenticateHandler;
use App\Http\Middleware\Filament\MustTwoFactor\MustTwoFactorHandler;

final class AdminPanelServiceProvider extends PanelServiceProvider
{
    public const ID = 'admin';

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id(self::ID)
            ->path(self::ID)
            ->homeUrl('/' . self::ID)
            ->brandName(Lang::get('admin.pages.panel.title'))
            ->discoverResources(in: app_path('Filament/Resources/Admin'), for: 'App\\Filament\\Resources\\Admin')
            ->discoverPages(in: app_path('Filament/Pages/Admin'), for: 'App\\Filament\\Pages\\Admin')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets/Admin'), for: 'App\\Filament\\Widgets\\Admin')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->authMiddleware([
                AuthenticateHandler::class,
                MustTwoFactorHandler::class,
                VerifyEmailHandler::class
            ], isPersistent: true)
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        condition: false,
                        shouldRegisterUserMenu: false,
                        shouldRegisterNavigation: false,
                        slug: 'profile'
                    )
                    ->enableTwoFactorAuthentication()
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling(null)
            ->spa();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): string => Blade::render('@vite("resources/js/admin.js")')
        );
    }
}
