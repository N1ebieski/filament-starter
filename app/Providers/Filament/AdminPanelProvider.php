<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Blade;
use Filament\Support\Facades\FilamentView;
use App\Filament\Http\Middleware\VerifyEmail;
use App\Filament\Http\Middleware\Authenticate;
use App\Filament\Http\Middleware\MustTwoFactor;
use App\Extends\Jeffgreco13\FilamentBreezy\BreezyCore;

final class AdminPanelProvider extends PanelProvider
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
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/Admin'), for: 'App\\Filament\\Widgets\\Admin')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                MustTwoFactor::class,
                VerifyEmail::class
            ])
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
