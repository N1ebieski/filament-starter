<?php

declare(strict_types=1);

namespace App\Providers\Filament\WebPanel;

use Filament\Panel;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Blade;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Support\Facades\FilamentView;
use App\Providers\Filament\PanelServiceProvider;
use App\Filament\Pages\Web\MyProfile\MyProfilePage;
use App\Overrides\Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Http\Middleware\Filament\EnsureEmailIsVerified\EnsureEmailIsVerifiedMiddleware;

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
                EnsureEmailIsVerifiedMiddleware::class
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
            ->topNavigation()
            ->spa()
            ->renderHook(
                'panels::styles.before',
                fn (): string => Vite::withEntryPoints(['resources/css/web.scss'])->toHtml()
            )
            ->renderHook(
                'panels::topbar.end',
                fn (): string => Blade::render('<x-web.topbar.login-button.login-button-component />')
            );
    }
}
