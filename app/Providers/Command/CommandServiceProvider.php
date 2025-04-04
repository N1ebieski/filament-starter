<?php

namespace App\Providers\Command;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(\App\Commands\CommandBusInterface::class, \App\Commands\CommandBus::class);
    }

    public function provides(): array
    {
        return [
            \App\Commands\CommandBusInterface::class,
        ];
    }
}
