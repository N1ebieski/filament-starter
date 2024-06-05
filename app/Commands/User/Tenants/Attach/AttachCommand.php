<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\Attach;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Support\Attributes\Handler\Handler;
use App\Models\Permission\Permission;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read Collection<Permission> $permissions
 */
#[Handler(\App\Commands\User\Tenants\Attach\AttachHandler::class)]
final class AttachCommand extends Command
{
    public function __construct(
        public readonly Tenant $tenant,
        public readonly User $user,
        public readonly Collection $permissions
    ) {
    }
}
