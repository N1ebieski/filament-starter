<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Scopes\Tenant\TenantScope::class, function (Application $app) {
            /** @var \App\Support\Tenant\CurrentTenantFactory */
            $factory = $app->make(\App\Support\Tenant\CurrentTenantFactory::class, [
                'filamentManager' => $app->make('filament')
            ]);

            return new \App\Scopes\Tenant\TenantScope($factory->make());
        });

        $this->app->bind(\App\Scopes\User\UserScope::class, function (Application $app) {
            /** @var \Illuminate\Contracts\Auth\Guard */
            $guard = $app->make(\Illuminate\Contracts\Auth\Guard::class);

            return new \App\Scopes\User\UserScope($guard->user());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
