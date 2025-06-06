<?php

declare(strict_types=1);

namespace App\Providers\Filament\Filament;

use Filament\Support\Facades\FilamentView;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

final class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn (): string => View::make('filament.footer.footer')->render()
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_NAV_START,
            fn (): string => View::make('filament.sidebar.close')->render()
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): string => Blade::render('@laravelPWA')
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Blade::render('<x-topbar.offline-state.offline-state-component />')
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Blade::render('<x-topbar.spotlight-button.spotlight-button-component />')
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Blade::render('<x-topbar.theme-switcher.theme-switcher-component />')
        );

        Table::configureUsing(function (Table $table): void {
            $table
                ->striped()
                ->recordUrl(null)
                ->recordAction(null)
                ->paginated([5, 10, 25, 50, 100])
                ->filtersLayout(FiltersLayout::AboveContentCollapsible);
        });
    }
}
