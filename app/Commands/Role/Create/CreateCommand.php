<?php

declare(strict_types=1);

namespace App\Commands\Role\Create;

use App\Commands\Command;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Collection;

final class CreateCommand extends Command
{
    public function __construct(
        public readonly string $name,
        public readonly Role $role = new Role(),
        public readonly Collection $permissions = new Collection()
    ) {
    }
}
