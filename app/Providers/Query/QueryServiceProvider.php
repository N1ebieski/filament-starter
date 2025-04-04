<?php

namespace App\Providers\Query;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(\App\Queries\QueryBusInterface::class, \App\Queries\QueryBus::class);
    }

    public function provides(): array
    {
        return [
            \App\Queries\QueryBusInterface::class,
        ];
    }
}
