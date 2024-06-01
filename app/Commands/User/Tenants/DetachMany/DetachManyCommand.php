<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\DetachMany;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Support\Attributes\Handler;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read Collection<User> $users
 */
#[Handler(\App\Commands\User\Tenants\DetachMany\DetachManyHandler::class)]
final class DetachManyCommand extends Command
{
    public function __construct(
        public readonly Tenant $tenant,
        public readonly Collection $users
    ) {
    }
}
