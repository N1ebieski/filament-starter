<?php

namespace App\Providers\Bus;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(\App\Overrides\Illuminate\Contracts\Bus\Dispatcher::class, function (Application $app) {
            /** @var \Illuminate\Bus\Dispatcher */
            $baseDispatcher = $app->make(\Illuminate\Contracts\Bus\Dispatcher::class);

            return new \App\Overrides\Illuminate\Bus\Dispatcher($baseDispatcher);
        });
    }

    public function provides(): array
    {
        return [
            \App\Overrides\Illuminate\Contracts\Bus\Dispatcher::class,
        ];
    }
}
