<?php

declare(strict_types=1);

namespace App\CacheQueries;

use App\Queries\QueryBus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * @property-read \App\Overrides\Illuminate\Contracts\Cache\Repository $cache
 */
abstract class Handler
{
    public function __construct(
        protected readonly Cache $cache,
        protected readonly Config $config,
        protected readonly Carbon $carbon,
        protected readonly Request $request,
        protected readonly QueryBus $queryBus
    ) {
    }
}
