<?php

declare(strict_types=1);

namespace App\Actions\PWA\GetAssets\Assets\PWAManifest;

use App\Actions\PWA\GetAssets\Assets\Assets;
use App\Actions\PWA\GetAssets\Assets\Handler;
use Closure;
use Illuminate\Contracts\Config\Repository as Config;

final class PWAManifestHandler extends Handler
{
    public function __construct(private readonly Config $config) {}

    public function handle(Assets $assets, Closure $next): Assets
    {
        /** @var array<int, array{path: string}> */
        $icons = $this->config->get('laravelpwa.manifest.icons');

        foreach ($icons as $icon) {
            $assets->value->push($icon['path']);
        }

        /** @var array<string, string> */
        $splashes = $this->config->get('laravelpwa.manifest.splash');

        foreach ($splashes as $splash) {
            $assets->value->push($splash);
        }

        /** @var array<int, array{src: string}> */
        $screenshots = $this->config->get('laravelpwa.manifest.custom.screenshots');

        foreach ($screenshots as $screenshot) {
            $assets->value->push($screenshot['src']);
        }

        return $next($assets);
    }
}
