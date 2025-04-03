<?php

declare(strict_types=1);

namespace App\CacheQueries\PWA\GetAssets;

use App\Actions\ActionBusInterface;
use App\CacheQueries\Handler;
use Illuminate\Contracts\Cache\Repository as Cache;

final readonly class GetAssetsHandler extends Handler
{
    public function __construct(
        private Cache $cache,
        private ActionBusInterface $actionBus
    ) {}

    public function handle(GetAssetsCacheQuery $cacheQuery): array
    {
        return $this->cache->remember(
            $cacheQuery->getKey(),
            $cacheQuery->time->minutes * 60,
            fn (): array => $this->actionBus->execute($cacheQuery->action)
        );
    }
}
