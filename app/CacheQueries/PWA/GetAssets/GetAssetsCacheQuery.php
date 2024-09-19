<?php

declare(strict_types=1);

namespace App\CacheQueries\PWA\GetAssets;

use App\CacheQueries\Time;
use App\CacheQueries\CacheQuery;
use App\Data\Casts\Time\TimeCast;
use Spatie\LaravelData\Attributes\WithCast;
use App\Actions\PWA\GetAssets\GetAssetsAction;

final class GetAssetsCacheQuery extends CacheQuery
{
    public function __construct(
        public readonly GetAssetsAction $action,
        #[WithCast(TimeCast::class)]
        public readonly Time $time
    ) {
    }

    public function getKey(): string
    {
        return "pwa.assets";
    }
}
