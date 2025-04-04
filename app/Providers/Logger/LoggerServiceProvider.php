<?php

namespace App\Providers\Logger;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(\App\Overrides\Illuminate\Contracts\Logger\LoggerInterface::class, function (Application $app) {
            /** @var \Illuminate\Log\LogManager */
            $logManager = $app->make(\Psr\Log\LoggerInterface::class);

            return new \App\Overrides\Illuminate\Log\LogManager($logManager);
        });
    }

    public function provides(): array
    {
        return [
            \App\Overrides\Illuminate\Contracts\Logger\LoggerInterface::class,
        ];
    }
}
