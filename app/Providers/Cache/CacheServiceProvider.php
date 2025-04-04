<?php

declare(strict_types=1);

namespace App\Providers\Cache;

use App\Providers\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

final class CacheServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(\App\Overrides\Illuminate\Contracts\Cache\Repository::class, function (Application $app) {
            /** @var \Illuminate\Cache\Repository */
            $cache = $app->make(\Illuminate\Contracts\Cache\Repository::class);

            return new \App\Overrides\Illuminate\Cache\Repository($cache);
        });

        $this->app->scoped(\App\CacheQueries\CacheQueryBusInterface::class, \App\CacheQueries\CacheQueryBus::class);
    }

    public function provides(): array
    {
        return [
            \App\Overrides\Illuminate\Contracts\Cache\Repository::class,
            \App\CacheQueries\CacheQueryBusInterface::class,
        ];
    }
}
