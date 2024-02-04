<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Support\Facades\FilamentView;

final class FilamentProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::global-search.before',
            fn (): string => Blade::render('<livewire:topbar.spotlight-button />')
        );

        Table::configureUsing(function (Table $table): void {
            $table
                ->striped()
                ->recordUrl(null)
                ->recordAction(null)
                ->filtersLayout(FiltersLayout::AboveContentCollapsible);
        });
    }
}
