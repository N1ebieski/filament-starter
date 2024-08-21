<?php

namespace App\Providers\App;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\Illuminate\Contracts\Pipeline\Pipeline::class, \Illuminate\Pipeline\Pipeline::class);

        $this->app->scoped(\App\Overrides\Illuminate\Contracts\Container\Container::class, function (Application $app) {
            return new \App\Overrides\Illuminate\Container\Container($app);
        });

        $this->app->bind(\App\Overrides\Illuminate\Contracts\Auth\Access\Gate::class, function (Application $app) {
            /** @var \Illuminate\Auth\Access\Gate */
            $gate = $app->make(\Illuminate\Contracts\Auth\Access\Gate::class);

            return new \App\Overrides\Illuminate\Auth\Access\Gate($gate);
        });

        $this->app->bind(\App\Overrides\Illuminate\Contracts\Auth\Guard::class, function (Application $app) {
            /** @var \Illuminate\Contracts\Auth\Guard */
            $guard = $app->make(\Illuminate\Contracts\Auth\Guard::class);

            return new \App\Overrides\Illuminate\Auth\Guard($guard);
        });

        $this->app->bind(\App\Overrides\Illuminate\Contracts\Filesystem\Factory::class, function (Application $app) {
            /** @var \Illuminate\Filesystem\FilesystemManager */
            $filesystemManager = $app->make(\Illuminate\Filesystem\FilesystemManager::class);

            return new \App\Overrides\Illuminate\Filesystem\FilesystemManager($filesystemManager);
        });

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

        $this->app->bind(\App\Overrides\Illuminate\Contracts\Logger\LoggerInterface::class, function (Application $app) {
            /** @var \Illuminate\Log\LogManager */
            $logManager = $app->make(\Psr\Log\LoggerInterface::class);

            return new \App\Overrides\Illuminate\Log\LogManager($logManager);
        });

        $this->app->bind(\App\Overrides\Illuminate\Contracts\Bus\Dispatcher::class, function (Application $app) {
            /** @var \Illuminate\Bus\Dispatcher */
            $baseDispatcher = $app->make(\Illuminate\Contracts\Bus\Dispatcher::class);

            return new \App\Overrides\Illuminate\Bus\Dispatcher($baseDispatcher);
        });

        $this->app->bind(\App\CacheQueries\CacheQueryBusInterface::class, \App\CacheQueries\CacheQueryBus::class);

        $this->app->bind(\App\Http\Clients\ClientBusInterface::class, \App\Http\Clients\ClientBus::class);

        $this->app->bind(\App\Commands\CommandBusInterface::class, \App\Commands\CommandBus::class);

        $this->app->bind(\App\Queries\QueryBusInterface::class, \App\Queries\QueryBus::class);

        $this->app->bind(\App\Scopes\Tenant\TenantScope::class, function (Application $app) {
            /** @var \App\Support\Tenant\CurrentTenantFactory */
            $factory = $app->make(\App\Tenant\CurrentTenantFactory::class, [
                'filamentManager' => $app->make('filament')
            ]);

            return new \App\Scopes\Tenant\TenantScope($factory->getTenant());
        });

        $this->app->bind(\App\Scopes\User\UserScope::class, function (Application $app) {
            /** @var \Illuminate\Contracts\Auth\Guard */
            $guard = $app->make(\Illuminate\Contracts\Auth\Guard::class);

            return new \App\Scopes\User\UserScope($guard->user());
        });
    }
}
