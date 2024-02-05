<?php

declare(strict_types=1);

namespace App\Policies\Tenant;

use App\Models\User\User;
use App\Models\Tenant\Tenant;

final class TenantPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Tenant $record): bool
    {
        return $user->id === $record->user->id;
    }

    public function delete(User $user, Tenant $record): bool
    {
        return $user->id === $record->user->id;
    }
}
