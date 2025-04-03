<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Auth;

use App\Overrides\Illuminate\Contracts\Auth\Guard as ContractsGuard;
use Illuminate\Contracts\Auth\Guard as BaseGuard;

final class Guard implements ContractsGuard
{
    public function __construct(private readonly BaseGuard $guard) {}

    /**
     * Get the currently authenticated user.
     *
     * @return \App\Models\User\User|null
     */
    public function user()
    {
        /** @var \App\Models\User\User|null */
        $user = $this->guard->user();

        return $user;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return $this->guard->check();
    }
}
