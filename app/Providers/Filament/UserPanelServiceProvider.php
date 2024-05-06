<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Panel;
use App\Models\Tenant\Tenant;
use Filament\Pages\Dashboard;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Lang;
use App\Http\Middleware\ApplyUserScope;
use Filament\Widgets\FilamentInfoWidget;
use App\Http\Middleware\ApplyTenantScope;
use App\Http\Middleware\Filament\VerifyEmail;
use App\Http\Middleware\Filament\Authenticate;
use App\Filament\Pages\User\Tenancy\EditTenant;
use App\Http\Middleware\Filament\MustTwoFactor;
use App\Filament\Pages\User\Tenancy\CreateTenant;
use App\Overrides\Jeffgreco13\FilamentBreezy\BreezyCore;

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
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/User'), for: 'App\\Filament\\Widgets\\User')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                MustTwoFactor::class,
                VerifyEmail::class,
                // ApplyUserScope::class
            ], isPersistent: true)
            ->tenantMiddleware([
                ApplyTenantScope::class
            ], isPersistent: true)
            ->tenant(Tenant::class)
            ->tenantRegistration(CreateTenant::class)
            ->tenantProfile(EditTenant::class)
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
