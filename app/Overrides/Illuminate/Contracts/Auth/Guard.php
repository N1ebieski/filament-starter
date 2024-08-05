<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Auth;

interface Guard
{
    /**
     * Get the currently authenticated user.
     *
     * @return \App\Models\User\User|null
     */
    public function user();

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check();
}
