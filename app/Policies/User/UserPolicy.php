<?php

declare(strict_types=1);

namespace App\Policies\User;

use App\Models\User\User;
use App\ValueObjects\Role\DefaultName;

final class UserPolicy
{
    public function create(User $authUser): bool
    {
        return $authUser->hasRole(DefaultName::SUPER_ADMIN->value);
    }

    public function edit(User $authUser, User $user): bool
    {
        return $authUser->hasRole(DefaultName::SUPER_ADMIN->value) && ($authUser->id !== $user->id);
    }

    public function delete(User $authUser, User $user): bool
    {
        return $authUser->hasRole(DefaultName::SUPER_ADMIN->value) && ($authUser->id !== $user->id);
    }

    public function deleteAny(User $authUser): bool
    {
        return $authUser->hasRole(DefaultName::SUPER_ADMIN->value);
        ;
    }

    public function toggleStatusEmail(User $authUser, User $user): bool
    {
        return $authUser->hasRole(DefaultName::SUPER_ADMIN->value) && ($authUser->id !== $user->id);
    }
}
