<?php

declare(strict_types=1);

namespace App\Services\PWA;

use App\Services\PWA\Assets\Assets;
use App\Services\PWA\Assets\Vite\ViteHandler;
use App\Services\PWA\Assets\Filament\FilamentHandler;
use App\Services\PWA\Assets\Livewire\LivewireHandler;
use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline;
use App\Overrides\Illuminate\Contracts\Container\Container;

final class PWAService
{
    public function __construct(
        private readonly Pipeline $pipeline,
        private readonly Container $container
    ) {
    }

    /**
     * Retrieves all assets required for a PWA implementation.
     *
     * @return array<string>
     */
    public function getAssets(): array
    {
        $pipes = $this->container->makeMany([
            FilamentHandler::class,
            LivewireHandler::class,
            ViteHandler::class
        ]);

        /** @var Assets */
        $assets = $this->pipeline->through($pipes)->process(new Assets());

        return $assets->value->toArray();
    }
}
