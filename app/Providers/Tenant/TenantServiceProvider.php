<?php

namespace App\Providers\Tenant;

use App\Models\Tenant\Tenant;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TenantServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->when(\App\Actions\Tenant\GetCurrent\GetCurrentHandler::class)
            ->needs(\Filament\FilamentManager::class)
            ->give(fn (Application $app) => $app->make('filament'));

        $this->app->bind(\App\GlobalScopes\Tenant\TenantScope::class, function (Application $app) {
            $handler = $app->make(\App\Actions\Tenant\GetCurrent\GetCurrentHandler::class);

            /** @var Tenant $tenant */
            $tenant = $handler->handle();

            return new \App\GlobalScopes\Tenant\TenantScope($tenant);
        });
    }

    public function provides(): array
    {
        return [
            \App\Actions\Tenant\GetCurrent\GetCurrentHandler::class,
            \App\GlobalScopes\Tenant\TenantScope::class,
        ];
    }
}
