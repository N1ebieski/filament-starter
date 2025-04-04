<?php

namespace App\Providers\Auth;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Role\Role::class => \App\Policies\Role\RolePolicy::class,
        \App\Models\User\User::class => \App\Policies\User\UserPolicy::class,
        \App\Models\Tenant\Tenant::class => \App\Policies\Tenant\TenantPolicy::class,
    ];

    public function register(): void
    {
        $this->app->register(DeferrableServiceProvider::class);

        parent::register();
    }
}
