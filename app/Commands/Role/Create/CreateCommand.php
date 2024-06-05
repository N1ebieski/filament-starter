<?php

declare(strict_types=1);

namespace App\Commands\Role\Create;

use App\Commands\Command;
use App\Models\Role\Role;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;

#[Handler(\App\Commands\Role\Create\CreateHandler::class)]
final class CreateCommand extends Command
{
    public function __construct(
        public readonly string $name,
        public readonly Role $role = new Role(),
        public readonly Collection $permissions = new Collection()
    ) {
    }
}
