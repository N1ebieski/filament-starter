<?php

declare(strict_types=1);

namespace App\Commands\User\Edit;

use App\Commands\Command;
use App\Models\Role\Role;
use App\Models\User\User;
use Spatie\LaravelData\Optional;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCastable;
use App\Data\Casts\CollectionOfModels\CollectionOfModels;

#[Handler(\App\Commands\User\Edit\EditHandler::class)]
final class EditCommand extends Command
{
    public function __construct(
        public readonly User $user,
        public readonly string|Optional $name = new Optional(),
        public readonly string|Optional $email = new Optional(),
        public readonly string|Optional $password = new Optional(),
        #[WithCastable(CollectionOfModels::class, Role::class)]
        public readonly Collection|Optional $roles = new Optional()
    ) {
    }
}
