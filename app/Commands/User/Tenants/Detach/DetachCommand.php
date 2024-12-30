<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\Detach;

use App\Commands\Command;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Support\Attributes\Handler\Handler;

#[Handler(\App\Commands\User\Tenants\Detach\DetachHandler::class)]
final class DetachCommand extends Command
{
    public function __construct(
        public readonly Tenant $tenant,
        public readonly User $user
    ) {}
}
