<?php

declare(strict_types=1);

namespace App\Services\PWA\Assets\Livewire;

use Closure;
use App\Services\PWA\Assets\Assets;
use App\Services\PWA\Assets\Handler;
use Illuminate\Filesystem\Filesystem;

final class LivewireHandler extends Handler
{
    public function __construct(private readonly Filesystem $filesystem)
    {
    }

    public function handle(Assets $assets, Closure $next): Assets
    {
        $manifestPath = base_path('vendor/livewire/livewire/dist/manifest.json');

        $files = json_decode($this->filesystem->get($manifestPath), true);

        $versionHash = $files['/livewire.js'];

        $assets->value->push("/livewire/livewire.js?id={$versionHash}");

        return $next($assets);
    }
}
