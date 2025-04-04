<?php

namespace App\Providers\Client;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->scoped(\App\Http\Clients\ClientBusInterface::class, \App\Http\Clients\ClientBus::class);

        $this->app->scoped(\App\Overrides\Illuminate\Contracts\Http\Client\Client::class, function (Application $app) {
            /** @var \App\Overrides\Illuminate\Contracts\Http\Client\Factory */
            $factory = $app->make(\App\Overrides\Illuminate\Contracts\Http\Client\Factory::class);

            return $factory->request();
        });

        $this->app->scoped(\App\Overrides\Illuminate\Contracts\Http\Client\Factory::class, function (Application $app) {
            /** @var \Illuminate\Http\Client\Factory */
            $factory = $app->make(\Illuminate\Http\Client\Factory::class);

            return new \App\Overrides\Illuminate\Http\Client\Factory($factory);
        });
    }

    public function provides(): array
    {
        return [
            \App\Http\Clients\ClientBusInterface::class,
            \App\Overrides\Illuminate\Contracts\Http\Client\Client::class,
            \App\Overrides\Illuminate\Contracts\Http\Client\Factory::class,
        ];
    }
}
