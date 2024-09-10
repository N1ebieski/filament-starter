<?php

declare(strict_types=1);

namespace App\Providers\Filament\UserPanel;

use Filament\Panel;
use App\Models\Tenant\Tenant;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Blade;
use Filament\Widgets\FilamentInfoWidget;
use App\Providers\Filament\PanelServiceProvider;
use App\Filament\Pages\User\Tenancy\Edit\EditTenantPage;
use App\Overrides\Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Filament\Pages\User\Tenancy\Create\CreateTenantPage;
use App\Http\Middleware\ApplyUserScope\ApplyUserScopeMiddleware;
use App\Http\Middleware\Filament\VerifyEmail\VerifyEmailMiddleware;
use App\Http\Middleware\ApplyTenantScope\ApplyTenantScopeMiddleware;
use App\Http\Middleware\Filament\Authenticate\AuthenticateMiddleware;
use App\Http\Middleware\Filament\MustTwoFactor\MustTwoFactorMiddleware;

final class UserPanelServiceProvider extends PanelServiceProvider
{
    public const ID = 'user';

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id(self::ID)
            ->path(self::ID)
            ->homeUrl('/' . self::ID)
            ->brandName(Lang::get('user.pages.panel.title')) //@phpstan-ignore-line
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
                ApplyTenantScopeMiddleware::class
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
            ->spa()
            ->renderHook(
                PanelsRenderHook::STYLES_BEFORE,
                fn (): string => Vite::withEntryPoints(['resources/css/user/user.scss'])->toHtml()
            );
    }
}
