<?php

declare(strict_types=1);

namespace App\Providers\App;

use App\Providers\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

final class DeferrableServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(\App\Overrides\Illuminate\Contracts\Container\Container::class, function (Application $app) {
            return new \App\Overrides\Illuminate\Container\Container($app);
        });
    }

    public function provides(): array
    {
        return [
            \App\Overrides\Illuminate\Contracts\Container\Container::class,
        ];
    }
}
