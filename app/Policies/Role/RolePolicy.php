<?php

declare(strict_types=1);

namespace App\Policies\Role;

use App\Models\Role\Role;
use App\Models\User\User;
use App\Policies\Policy;
use App\ValueObjects\Role\Name\DefaultName;

final class RolePolicy extends Policy
{
    public function viewAny(User $user): bool
    {
        return $user->can('admin.role.view');
    }

    public function create(User $user): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value);
    }

    public function update(User $user, Role $record): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value)
            && ! $record->name->isAdmin();
    }

    public function delete(User $user, Role $record): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value)
            && ! $record->name->isDefault();
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value);
    }
}
