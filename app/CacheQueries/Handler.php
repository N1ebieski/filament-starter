<?php

declare(strict_types=1);

namespace App\CacheQueries;

use App\Queries\QueryBus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;

abstract class Handler
{
    public function __construct(
        protected Cache $cache,
        protected Config $config,
        protected Carbon $carbon,
        protected Request $request,
        protected QueryBus $queryBus
    ) {
    }
}
