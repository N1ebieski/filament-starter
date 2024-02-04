<?php

declare(strict_types=1);

namespace App\Policies\User;

use App\Models\User\User;
use App\ValueObjects\Role\DefaultName;

final class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('admin.user.view');
    }

    public function create(User $user): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value);
    }

    public function update(User $user, User $record): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value) && ($user->id !== $record->id);
    }

    public function delete(User $user, User $record): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value) && ($user->id !== $record->id);
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value);
    }

    public function toggleStatusEmail(User $user, User $record): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value) && ($user->id !== $record->id);
    }
}
