<?php

declare(strict_types=1);

namespace App\Commands\Role\Edit;

use App\Commands\Command;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Collection;

final class EditCommand extends Command
{
    public function __construct(
        public readonly Role $role,
        public readonly string $name,
        public readonly Collection $permissions
    ) {
    }
}
