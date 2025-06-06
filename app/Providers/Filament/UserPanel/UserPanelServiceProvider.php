<?php

declare(strict_types=1);

namespace App\Providers\Filament\UserPanel;

use App\Filament\Pages\User\Tenancy\Create\CreateTenantPage;
use App\Filament\Pages\User\Tenancy\Edit\EditTenantPage;
use App\Http\Middleware\ApplyTenantScope\ApplyTenantScopeMiddleware;
use App\Http\Middleware\Filament\Authenticate\AuthenticateMiddleware;
use App\Http\Middleware\Filament\MustTwoFactor\MustTwoFactorMiddleware;
use App\Http\Middleware\Filament\VerifyEmail\VerifyEmailMiddleware;
use App\Models\Tenant\Tenant;
use App\Overrides\Illuminate\Support\Facades\Lang;
use App\Overrides\Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Providers\Filament\PanelServiceProvider;
use Filament\Panel;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

final class UserPanelServiceProvider extends PanelServiceProvider
{
    public const ID = 'user';

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id(self::ID)
            ->path(self::ID)
            ->homeUrl('/'.self::ID)
            ->brandName(Lang::string('user.pages.panel.title'))
            ->discoverResources(in: app_path('Filament/Resources/User'), for: 'App\\Filament\\Resources\\User')
            ->discoverPages(in: app_path('Filament/Pages/User'), for: 'App\\Filament\\Pages\\User')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets/User'), for: 'App\\Filament\\Widgets\\User')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->authMiddleware([
                AuthenticateMiddleware::class,
                MustTwoFactorMiddleware::class,
                VerifyEmailMiddleware::class,
                // ApplyUserScope::class
            ], isPersistent: true)
            ->tenantMiddleware([
                ApplyTenantScopeMiddleware::class,
            ], isPersistent: true)
            ->tenant(Tenant::class)
            ->tenantRegistration(CreateTenantPage::class)
            ->tenantProfile(EditTenantPage::class)
            ->tenantRoutePrefix('tenants')
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        condition: false,
                        shouldRegisterUserMenu: false,
                        shouldRegisterNavigation: false,
                        slug: 'profile',
                    )
                    ->enableTwoFactorAuthentication(),
            ])
            ->spa()
            ->viteTheme('resources/css/user/user.scss');
    }
}
