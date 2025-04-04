<?php

declare(strict_types=1);

namespace App\Providers\Action;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class ActionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(\App\Actions\ActionBusInterface::class, \App\Actions\ActionBus::class);
    }

    public function provides(): array
    {
        return [
            \App\Actions\ActionBusInterface::class,
        ];
    }
}
