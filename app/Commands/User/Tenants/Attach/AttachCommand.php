<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\Attach;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Data\Casts\Model\ModelCast;
use App\Models\Permission\Permission;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;

/**
 * @property-read Collection<Permission> $permissions
 */
#[Handler(\App\Commands\User\Tenants\Attach\AttachHandler::class)]
final class AttachCommand extends Command
{
    public function __construct(
        #[WithCast(ModelCast::class, Tenant::class)]
        public readonly Tenant $tenant,
        #[WithCast(ModelCast::class, User::class)]
        public readonly User $user,
        #[WithCast(CollectionOfModelsCast::class, Permission::class)]
        public readonly Collection $permissions
    ) {
    }
}
