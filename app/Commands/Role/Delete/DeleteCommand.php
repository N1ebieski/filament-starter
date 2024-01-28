<?php

declare(strict_types=1);

namespace App\Commands\Role\Delete;

use App\Commands\Command;
use App\Models\Role\Role;

final class DeleteCommand extends Command
{
    public function __construct(public readonly Role $role)
    {
    }
}
