<?php

declare(strict_types=1);

namespace App\Policies\User;

use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\ValueObjects\Role\Name\DefaultName;

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

    public function tenantAttach(User $user, Tenant $tenant): bool
    {
        return $tenant->user?->id === $user->id;
    }

    public function tenantDetach(User $user, User $record, Tenant $tenant): bool
    {
        return $tenant->user?->id === $user->id && $user->id !== $record->id;
    }

    public function tenantDetachAny(User $user, Tenant $tenant): bool
    {
        return $tenant->user?->id === $user->id;
    }

    public function tenantUpdatePermissions(User $user, User $record, Tenant $tenant): bool
    {
        return $tenant->user?->id === $user->id && $user->id !== $record->id;
    }
}
