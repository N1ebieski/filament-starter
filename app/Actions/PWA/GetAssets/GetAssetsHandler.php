<?php

declare(strict_types=1);

namespace App\Actions\PWA\GetAssets;

use App\Actions\Handler;
use App\Actions\PWA\GetAssets\Assets\Assets;
use App\Actions\PWA\GetAssets\Assets\Filament\FilamentHandler;
use App\Actions\PWA\GetAssets\Assets\Livewire\LivewireHandler;
use App\Actions\PWA\GetAssets\Assets\PWACache\PWACacheHandler;
use App\Actions\PWA\GetAssets\Assets\PWAManifest\PWAManifestHandler;
use App\Actions\PWA\GetAssets\Assets\Vite\ViteHandler;
use App\Overrides\Illuminate\Contracts\Container\Container;
use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline;

final class GetAssetsHandler extends Handler
{
    public function __construct(
        private readonly Pipeline $pipeline,
        private readonly Container $container
    ) {}

    /**
     * Retrieves all assets required for a PWA implementation.
     *
     * @return array<string>
     */
    public function handle(GetAssetsAction $action): array
    {
        $pipes = $this->container->makeMany([
            FilamentHandler::class,
            LivewireHandler::class,
            ViteHandler::class,
            PWAManifestHandler::class,
            PWACacheHandler::class,
        ]);

        /** @var Assets */
        $assets = $this->pipeline->through($pipes)->process(new Assets);

        return $assets->value->toArray();
    }
}
