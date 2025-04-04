<?php

declare(strict_types=1);

namespace App\Policies\User;

use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Policies\Policy;
use App\ValueObjects\Role\Name\DefaultName;

final class UserPolicy extends Policy
{
    public function adminViewAny(User $user): bool
    {
        return $user->can('admin.user.view');
    }

    public function adminCreate(User $user): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value);
    }

    public function adminUpdate(User $user, User $record): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value) && ($user->id !== $record->id);
    }

    public function adminDelete(User $user, User $record): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value) && ($user->id !== $record->id);
    }

    public function adminDeleteAny(User $user): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value);
    }

    public function adminToggleStatusEmail(User $user, User $record): bool
    {
        return $user->hasRole(DefaultName::SuperAdmin->value) && ($user->id !== $record->id);
    }

    public function userTenantAttach(User $user, Tenant $tenant): bool
    {
        return $tenant->user?->id === $user->id;
    }

    public function userTenantDetach(User $user, User $record, Tenant $tenant): bool
    {
        return $tenant->user?->id === $user->id && $user->id !== $record->id;
    }

    public function userTenantDetachAny(User $user, Tenant $tenant): bool
    {
        return $tenant->user?->id === $user->id;
    }

    public function userTenantUpdatePermissions(User $user, User $record, Tenant $tenant): bool
    {
        return $tenant->user?->id === $user->id && $user->id !== $record->id;
    }
}
