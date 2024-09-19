<?php

declare(strict_types=1);

namespace App\CacheQueries\PWA\GetAssets;

use App\CacheQueries\Handler;
use App\Actions\ActionBusInterface;
use Illuminate\Contracts\Cache\Repository as Cache;

final class GetAssetsHandler extends Handler
{
    public function __construct(
        private readonly Cache $cache,
        private readonly ActionBusInterface $actionBus
    ) {
    }

    public function handle(GetAssetsCacheQuery $cacheQuery): array
    {
        $assets = $this->cache->remember(
            $cacheQuery->getKey(),
            $cacheQuery->time->minutes * 60,
            function () use ($cacheQuery): array {
                return $this->actionBus->execute($cacheQuery->action);
            }
        );

        return $assets;
    }
}
