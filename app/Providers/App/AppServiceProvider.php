<?php

namespace App\Providers\App;

use App\Models\Tenant\Tenant;
use App\Models\User\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Actions\ActionBusInterface::class, \App\Actions\ActionBus::class);

        $this->app->bind(\App\Overrides\Illuminate\Contracts\Cache\Repository::class, function (Application $app) {
            /** @var \Illuminate\Cache\Repository */
            $cache = $app->make(\Illuminate\Contracts\Cache\Repository::class);

            return new \App\Overrides\Illuminate\Cache\Repository($cache);
        });

        $this->app->bind(\App\Overrides\Illuminate\Contracts\Http\Client\Client::class, function (Application $app) {
            /** @var \App\Overrides\Illuminate\Contracts\Http\Client\Factory */
            $factory = $app->make(\App\Overrides\Illuminate\Contracts\Http\Client\Factory::class);

            return $factory->request();
        });

        $this->app->bind(\App\Overrides\Illuminate\Contracts\Http\Client\Factory::class, function (Application $app) {
            /** @var \Illuminate\Http\Client\Factory */
            $factory = $app->make(\Illuminate\Http\Client\Factory::class);

            return new \App\Overrides\Illuminate\Http\Client\Factory($factory);
        });

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
            /** @var \App\Tenant\CurrentTenantFactory */
            $factory = $app->make(\App\Tenant\CurrentTenantFactory::class, [
                'filamentManager' => $app->make('filament'),
            ]);

            /** @var Tenant */
            $tenant = $factory->getTenant();

            return new \App\Scopes\Tenant\TenantScope($tenant);
        });

        $this->app->bind(\App\Scopes\User\UserScope::class, function (Application $app) {
            /** @var \Illuminate\Contracts\Auth\Guard */
            $guard = $app->make(\Illuminate\Contracts\Auth\Guard::class);

            /** @var User */
            $user = $guard->user();

            return new \App\Scopes\User\UserScope($user);
        });
    }
}
