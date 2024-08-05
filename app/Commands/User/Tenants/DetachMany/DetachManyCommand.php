<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\DetachMany;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Data\Casts\Model\ModelCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;

/**
 * @property-read Collection<User> $users
 */
#[Handler(\App\Commands\User\Tenants\DetachMany\DetachManyHandler::class)]
final class DetachManyCommand extends Command
{
    public function __construct(
        #[WithCast(ModelCast::class, Tenant::class)]
        public readonly Tenant $tenant,
        #[WithCast(CollectionOfModelsCast::class, User::class)]
        public readonly Collection $users
    ) {
    }
}
