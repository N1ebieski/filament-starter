<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\View\Metas\MetaInterface;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\View\View as ContractView;
use Illuminate\Support\Facades\View;

trait HasMeta
{
    public function renderingHasMeta(): void
    {
        FilamentView::registerRenderHook(
            'panels::head.start',
            fn (): ContractView => View::make('meta.meta', ['meta' => $this->getMeta()])
        );
    }

    public function updatedPage(): void
    {
        $this->dispatch('update-meta', meta: $this->getMeta());
    }

    abstract public function getMeta(): MetaInterface;
}
