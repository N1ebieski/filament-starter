<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\Attach;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Models\Permission\Permission;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCastable;
use App\Data\Casts\ModelCollectionOf\ModelCollectionOf;

/**
 * @property-read Collection<Permission> $permissions
 */
#[Handler(\App\Commands\User\Tenants\Attach\AttachHandler::class)]
final class AttachCommand extends Command
{
    public function __construct(
        public readonly Tenant $tenant,
        public readonly User $user,
        #[WithCastable(ModelCollectionOf::class, Permission::class)]
        public readonly Collection $permissions
    ) {
    }
}
