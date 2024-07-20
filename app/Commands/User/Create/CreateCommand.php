<?php

declare(strict_types=1);

namespace App\Commands\User\Create;

use App\Commands\Command;
use App\Models\Role\Role;
use App\Models\User\User;
use Illuminate\Support\Collection;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCastable;
use App\Data\Casts\CollectionOfModels\CollectionOfModels;

#[Handler(\App\Commands\User\Create\CreateHandler::class)]
final class CreateCommand extends Command
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly User $user = new User(),
        #[WithCastable(CollectionOfModels::class, Role::class)]
        public readonly Collection $roles = new Collection()
    ) {
    }
}
