<?php

declare(strict_types=1);

namespace App\Overrides\LaravelPWA\Services;

use Illuminate\Contracts\Config\Repository as Config;
use LaravelPWA\Services\ManifestService as BaseManifestService;

final readonly class ManifestService
{
    public function __construct(
        private BaseManifestService $manifestService,
        private Config $config
    ) {}

    public function generate(): array
    {
        $baseManifest = $this->manifestService->generate();

        $baseManifest['icons'] = [];

        foreach ($this->config->get('laravelpwa.manifest.icons') as $file) {
            /** @var array{extension: string} $fileInfo */
            $fileInfo = pathinfo((string) $file['path']);

            $baseManifest['icons'][] = [
                'src' => $file['path'],
                'type' => 'image/'.$fileInfo['extension'],
                'sizes' => $file['sizes'],
                'purpose' => $file['purpose'],
            ];
        }

        return $baseManifest;
    }
}
