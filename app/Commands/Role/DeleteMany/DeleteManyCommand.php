<?php

declare(strict_types=1);

namespace App\Commands\Role\DeleteMany;

use App\Commands\Command;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Models\Role\Role;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * @property-read Collection<Role> $roles
 */
#[Handler(\App\Commands\Role\DeleteMany\DeleteManyHandler::class)]
final class DeleteManyCommand extends Command
{
    public function __construct(
        #[WithCast(CollectionOfModelsCast::class, Role::class)]
        public readonly Collection $roles
    ) {}
}
