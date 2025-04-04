<?php

declare(strict_types=1);

namespace App\Providers\Auth;

use App\Models\User\User;
use App\Providers\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

final class DeferrableServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
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

        $this->app->bind(\App\GlobalScopes\User\UserScope::class, function (Application $app) {
            /** @var \Illuminate\Contracts\Auth\Guard */
            $guard = $app->make(\Illuminate\Contracts\Auth\Guard::class);

            /** @var User $user */
            $user = $guard->user();

            return new \App\GlobalScopes\User\UserScope($user);
        });
    }

    public function provides(): array
    {
        return [
            \App\Overrides\Illuminate\Contracts\Auth\Access\Gate::class,
            \App\Overrides\Illuminate\Contracts\Auth\Guard::class,
            \App\GlobalScopes\User\UserScope::class,
        ];
    }
}
