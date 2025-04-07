<?php

declare(strict_types=1);

namespace App\Providers\Config;

use App\Providers\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

final class ConfigServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(\App\Overrides\Illuminate\Contracts\Config\Repository::class, function (Application $app) {
            /** @var \Illuminate\Config\Repository */
            $config = $app->make(\Illuminate\Contracts\Config\Repository::class);

            return new \App\Overrides\Illuminate\Config\Repository($config);
        });
    }

    public function provides(): array
    {
        return [
            \App\Overrides\Illuminate\Contracts\Config\Repository::class,
        ];
    }
}
