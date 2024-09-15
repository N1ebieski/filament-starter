<?php

declare(strict_types=1);

namespace App\Actions\PWA\GetAssets\Assets\Vite;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use App\Actions\PWA\GetAssets\Assets\Assets;
use App\Actions\PWA\GetAssets\Assets\Handler;

final class ViteHandler extends Handler
{
    public function __construct(private readonly Filesystem $filesystem)
    {
    }

    public function handle(Assets $assets, Closure $next): Assets
    {
        $manifestPath = public_path('build/manifest.json');

        if ($this->filesystem->exists($manifestPath)) {
            /** @var array<int, array{file: string}> */
            $manifestAsArray = json_decode($this->filesystem->get($manifestPath), true);

            $files = Collection::make($manifestAsArray)
                ->map(fn (array $asset): string => $asset['file'])
                ->values()
                ->unique();

            $files->each(function (string $file) use ($assets): void {
                $assets->value->push('/build/' . $file);
            });
        }

        return $next($assets);
    }
}
