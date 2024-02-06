<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\DetachMulti;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read Collection<User> $users
 */
final class DetachMultiCommand extends Command
{
    public function __construct(
        public readonly Tenant $tenant,
        public readonly Collection $users
    ) {
    }
}
