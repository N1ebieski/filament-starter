<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\Detach;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Data\Casts\Model\ModelCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;

#[Handler(\App\Commands\User\Tenants\Detach\DetachHandler::class)]
final class DetachCommand extends Command
{
    public function __construct(
        #[WithCast(ModelCast::class, Tenant::class)]
        public readonly Tenant $tenant,
        #[WithCast(ModelCast::class, User::class)]
        public readonly User $user
    ) {
    }
}
