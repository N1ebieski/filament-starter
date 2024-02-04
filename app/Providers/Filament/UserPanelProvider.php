<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\Lang;
use App\Http\Middleware\Filament\VerifyEmail;
use App\Http\Middleware\Filament\Authenticate;
use App\Http\Middleware\Filament\MustTwoFactor;
use App\Filament\Pages\User\Tenancy\RegisterTeam;
use App\Extends\Jeffgreco13\FilamentBreezy\BreezyCore;

final class UserPanelProvider extends PanelProvider
{
    public const ID = 'user';

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id(self::ID)
            ->path(self::ID)
            ->homeUrl('/' . self::ID)
            ->brandName(Lang::get('user.pages.index.title'))
            ->discoverResources(in: app_path('Filament/Resources/User'), for: 'App\\Filament\\Resources\\User')
            ->discoverPages(in: app_path('Filament/Pages/User'), for: 'App\\Filament\\Pages\\User')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/User'), for: 'App\\Filament\\Widgets\\User')
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
                        shouldRegisterUserMenu: false,
                        shouldRegisterNavigation: true,
                        hasAvatars: false,
                        slug: 'profile',
                        navigationGroup: Lang::get('user.groups.settings')
                    )
                    ->enableTwoFactorAuthentication()
            ])
            ->tenant(Tenant::class)
            ->tenantRegistration(RegisterTeam::class)
            ->tenantRoutePrefix('team')
            ->spa();
    }
}
