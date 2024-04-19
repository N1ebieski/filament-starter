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
        $this->app->bind(\App\Overrides\Illuminate\Contracts\Chain\Chain::class, function (Application $app) {
            return $app->make(\App\Overrides\Illuminate\Pipeline\Pipeline::class);
        });

        $this->app->bind(\App\Overrides\Illuminate\Contracts\Pipeline\Pipeline::class, function (Application $app) {
            return $app->make(\App\Overrides\Illuminate\Pipeline\Pipeline::class);
        });

        $this->app->scoped(\App\Overrides\Illuminate\Contracts\Container\Container::class, function (Application $app) {
            return new \App\Overrides\Illuminate\Container\Container($app);
        });

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
}
