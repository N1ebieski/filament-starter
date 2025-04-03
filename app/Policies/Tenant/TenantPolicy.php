<?php

declare(strict_types=1);

namespace App\Policies\Tenant;

use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Policies\Policy;

final readonly class TenantPolicy extends Policy
{
    public function create(): bool
    {
        return true;
    }

    public function update(User $user, Tenant $record): bool
    {
        return $user->id === $record->user?->id;
    }

    public function delete(User $user, Tenant $record): bool
    {
        return $user->id === $record->user?->id;
    }

    public function usersViewAny(User $user, Tenant $record): bool
    {
        return $user->id === $record->user?->id;
    }
}
