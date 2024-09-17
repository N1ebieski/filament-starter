<?php

declare(strict_types=1);

namespace App\CacheQueries\PWA\GetAssets;

use App\CacheQueries\Handler;
use App\Actions\ActionBusInterface;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;

final class GetAssetsHandler extends Handler
{
    public function __construct(
        private readonly Cache $cache,
        private readonly Config $config,
        private readonly ActionBusInterface $actionBus
    ) {
    }

    public function handle(GetAssetsCacheQuery $cacheQuery): array
    {
        $minutes = $cacheQuery->time?->minutes ?? $this->config->get('cache.minutes');

        $assets = $this->cache->remember(
            $cacheQuery->getKey(),
            $minutes * 60,
            function () use ($cacheQuery): array {
                return $this->actionBus->execute($cacheQuery->action);
            }
        );

        return $assets;
    }
}
