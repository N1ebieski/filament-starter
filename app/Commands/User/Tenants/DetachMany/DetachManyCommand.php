<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\DetachMany;

use App\Commands\Command;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * @property-read Collection<User> $users
 */
#[Handler(\App\Commands\User\Tenants\DetachMany\DetachManyHandler::class)]
final class DetachManyCommand extends Command
{
    public function __construct(
        public readonly Tenant $tenant,
        #[WithCast(CollectionOfModelsCast::class, User::class)]
        public readonly Collection $users
    ) {}
}
