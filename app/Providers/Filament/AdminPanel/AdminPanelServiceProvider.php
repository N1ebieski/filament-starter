<?php

declare(strict_types=1);

namespace App\Providers\Filament\AdminPanel;

use App\Http\Middleware\Filament\Authenticate\AuthenticateMiddleware;
use App\Http\Middleware\Filament\MustTwoFactor\MustTwoFactorMiddleware;
use App\Http\Middleware\Filament\VerifyEmail\VerifyEmailMiddleware;
use App\Overrides\Illuminate\Support\Facades\Lang;
use App\Overrides\Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Providers\Filament\PanelServiceProvider;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Support\Facades\Vite;

final class AdminPanelServiceProvider extends PanelServiceProvider
{
    public const ID = 'admin';

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id(self::ID)
            ->path(self::ID)
            ->homeUrl('/'.self::ID)
            ->brandName(Lang::string('admin.pages.panel.title'))
            ->discoverResources(in: app_path('Filament/Resources/Admin'), for: 'App\\Filament\\Resources\\Admin')
            ->discoverPages(in: app_path('Filament/Pages/Admin'), for: 'App\\Filament\\Pages\\Admin')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets/Admin'), for: 'App\\Filament\\Widgets\\Admin')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->authMiddleware([
                AuthenticateMiddleware::class,
                MustTwoFactorMiddleware::class,
                VerifyEmailMiddleware::class,
            ], isPersistent: true)
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        condition: false,
                        shouldRegisterUserMenu: false,
                        shouldRegisterNavigation: false,
                        slug: 'profile'
                    )
                    ->enableTwoFactorAuthentication(),
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling(null)
            ->spa()
            ->viteTheme('resources/css/admin/admin.scss')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Vite::withEntryPoints(['resources/js/admin/admin.js'])->toHtml()
            );
    }
}
