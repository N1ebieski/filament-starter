<?php

declare(strict_types=1);

namespace App\Providers\Filament\UserPanel;

use Filament\Panel;
use App\Models\Tenant\Tenant;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Lang;
use Filament\Widgets\FilamentInfoWidget;
use App\Providers\Filament\PanelServiceProvider;
use App\Filament\Pages\User\Tenancy\Edit\EditTenantPage;
use App\Filament\Pages\User\Tenancy\Create\CreateTenantPage;
use App\Overrides\Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Http\Middleware\ApplyUserScope\ApplyUserScopeHandler;
use App\Http\Middleware\Filament\VerifyEmail\VerifyEmailHandler;
use App\Http\Middleware\ApplyTenantScope\ApplyTenantScopeHandler;
use App\Http\Middleware\Filament\Authenticate\AuthenticateHandler;
use App\Http\Middleware\Filament\MustTwoFactor\MustTwoFactorHandler;

final class UserPanelServiceProvider extends PanelServiceProvider
{
    public const ID = 'user';

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id(self::ID)
            ->path(self::ID)
            ->homeUrl('/' . self::ID)
            ->brandName(Lang::get('user.pages.panel.title'))
            ->discoverResources(in: app_path('Filament/Resources/User'), for: 'App\\Filament\\Resources\\User')
            ->discoverPages(in: app_path('Filament/Pages/User'), for: 'App\\Filament\\Pages\\User')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets/User'), for: 'App\\Filament\\Widgets\\User')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->authMiddleware([
                AuthenticateHandler::class,
                MustTwoFactorHandler::class,
                VerifyEmailHandler::class,
                // ApplyUserScope::class
            ], isPersistent: true)
            ->tenantMiddleware([
                ApplyTenantScopeHandler::class
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
                    ->enableTwoFactorAuthentication()
            ])
            ->spa();
    }
}
