<?php

declare(strict_types=1);

namespace App\Commands\User\Create;

use App\Commands\Command;
use App\Models\Role\Role;
use App\Models\User\User;
use Illuminate\Support\Collection;
use App\Support\Attributes\Handler\Handler;
use App\Data\ObjectDefaultsInterface;
use Spatie\LaravelData\Attributes\WithCast;
use App\Data\Casts\ModelCollectionOf\ModelCollectionOfCast;

#[Handler(\App\Commands\User\Create\CreateHandler::class)]
final class CreateCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly User $user = new User(),
        #[WithCast(ModelCollectionOfCast::class, Role::class)]
        public readonly Collection $roles = new Collection()
    ) {
    }
}
