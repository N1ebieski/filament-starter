<?php

declare(strict_types=1);

namespace App\Providers\Filament\WebPanel;

use App\Filament\Pages\Web\MyProfile\MyProfilePage;
use App\Http\Middleware\Filament\EnsureEmailIsVerified\EnsureEmailIsVerifiedMiddleware;
use App\Overrides\Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Providers\Filament\PanelServiceProvider;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;

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
                    ->myProfileComponents([
                        'personal_info' => \App\Overrides\Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo::class,
                    ])
                    ->customMyProfilePage(MyProfilePage::class)
                    ->enableTwoFactorAuthentication(),
            ])
            ->topNavigation()
            ->spa()
            ->viteTheme('resources/css/web/web.scss')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Vite::withEntryPoints(['resources/js/web/web.js'])->toHtml()
            )
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn (): string => Blade::render('<x-web.topbar.login-button.login-button-component />')
            );
    }
}
