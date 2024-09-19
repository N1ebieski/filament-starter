<?php

declare(strict_types=1);

namespace App\Actions\PWA\GetAssets\Assets\Filament;

use App\Actions\PWA\GetAssets\Assets\Assets;
use App\Actions\PWA\GetAssets\Assets\Handler;
use Closure;
use Composer\InstalledVersions;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

final class FilamentHandler extends Handler
{
    public function __construct(private readonly Filesystem $filesystem) {}

    private function getDirectories(): array
    {
        return [
            'filament/filament' => [
                public_path('css/filament/filament'),
                public_path('js/filament/filament'),
            ],
            'filament/forms' => [
                public_path('css/filament/forms'),
                public_path('js/filament/forms'),
            ],
            'filament/support' => [
                public_path('css/filament/support'),
                public_path('js/filament/support'),
            ],
            'filament/notifications' => [
                public_path('css/filament/notifications'),
                public_path('js/filament/notifications'),
            ],
            'filament/tables' => [
                public_path('css/filament/tables'),
                public_path('js/filament/tables'),
            ],
            'filament/widgets' => [
                public_path('css/filament/widgets'),
                public_path('js/filament/widgets'),
            ],
            'pxlrbt/filament-spotlight' => [
                public_path('css/pxlrbt/filament-spotlight'),
                public_path('js/pxlrbt/filament-spotlight'),
            ],
        ];
    }

    public function handle(Assets $assets, Closure $next): Assets
    {
        foreach ($this->getDirectories() as $package => $directories) {
            foreach ($directories as $directory) {
                if (! $this->filesystem->exists($directory)) {
                    continue;
                }

                $files = $this->filesystem->allFiles($directory);

                foreach ($files as $file) {
                    $asset = Str::after($file->getPathname(), '/public');
                    $asset .= '?v='.InstalledVersions::getVersion($package);

                    $assets->value->push($asset);
                }
            }
        }

        return $next($assets);
    }
}
