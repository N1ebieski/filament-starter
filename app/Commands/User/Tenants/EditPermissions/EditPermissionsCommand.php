<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\EditPermissions;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Models\Permission\Permission;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Casts\ModelCollectionOf\ModelCollectionOfCast;

/**
 * @property-read Collection<Permission> $permissions
 */
#[Handler(\App\Commands\User\Tenants\EditPermissions\EditPermissionsHandler::class)]
final class EditPermissionsCommand extends Command
{
    public function __construct(
        public readonly Tenant $tenant,
        public readonly User $user,
        #[WithCast(ModelCollectionOfCast::class, Permission::class)]
        public readonly Collection $permissions
    ) {
    }
}
