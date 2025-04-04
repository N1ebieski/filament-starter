<?php

declare(strict_types=1);

namespace App\Policies\Tenant;

use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Policies\Policy;

final class TenantPolicy extends Policy
{
    public function userCreate(): bool
    {
        return true;
    }

    public function userUpdate(User $user, Tenant $record): bool
    {
        return $user->id === $record->user?->id;
    }

    public function userDelete(User $user, Tenant $record): bool
    {
        return $user->id === $record->user?->id;
    }

    public function userUsersViewAny(User $user, Tenant $record): bool
    {
        return $user->id === $record->user?->id;
    }
}
