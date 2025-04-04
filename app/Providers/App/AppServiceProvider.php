<?php

namespace App\Providers\App;

use App\Providers\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
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
