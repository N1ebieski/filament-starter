<?php

declare(strict_types=1);

namespace App\Commands\User\Create;

use App\Commands\Command;
use App\Models\User\User;
use App\Support\Attributes\Handler;
use Illuminate\Database\Eloquent\Collection;

#[Handler(\App\Commands\User\Create\CreateHandler::class)]
final class CreateCommand extends Command
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly User $user = new User(),
        public readonly Collection $roles = new Collection()
    ) {
    }
}
