<?php

namespace App\Providers\Pipeline;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PipelineServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(\Illuminate\Contracts\Pipeline\Pipeline::class, \Illuminate\Pipeline\Pipeline::class);

        $this->app->bind(\App\Overrides\Illuminate\Contracts\Chain\Chain::class, function (Application $app) {
            /** @var \Illuminate\Pipeline\Pipeline */
            $pipeline = $app->make(\Illuminate\Contracts\Pipeline\Pipeline::class);

            return new \App\Overrides\Illuminate\Pipeline\Pipeline($pipeline);
        });

        $this->app->bind(\App\Overrides\Illuminate\Contracts\Pipeline\Pipeline::class, function (Application $app) {
            /** @var \Illuminate\Pipeline\Pipeline */
            $pipeline = $app->make(\Illuminate\Contracts\Pipeline\Pipeline::class);

            return new \App\Overrides\Illuminate\Pipeline\Pipeline($pipeline);
        });
    }

    public function provides(): array
    {
        return [
            \Illuminate\Contracts\Pipeline\Pipeline::class,
            \App\Overrides\Illuminate\Contracts\Chain\Chain::class,
            \App\Overrides\Illuminate\Contracts\Pipeline\Pipeline::class,
        ];
    }
}
