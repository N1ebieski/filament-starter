<?php

declare(strict_types=1);

namespace App\Services\PWA\Assets\Vite;

use Closure;
use Illuminate\Support\Collection;
use App\Services\PWA\Assets\Assets;
use App\Services\PWA\Assets\Handler;
use Illuminate\Filesystem\Filesystem;

final class ViteHandler extends Handler
{
    public function __construct(private readonly Filesystem $filesystem)
    {
    }

    public function handle(Assets $assets, Closure $next): Assets
    {
        $manifestPath = public_path('build/manifest.json');

        if ($this->filesystem->exists($manifestPath)) {
            $files = Collection::make(
                json_decode($this->filesystem->get($manifestPath), true)
            )->map(function (array $asset) {
                return $asset['file'];
            })
            ->values()
            ->unique();

            $files->each(function (string $file) use ($assets) {
                $assets->value->push('/build/' . $file);
            });
        }

        return $next($assets);
    }
}
