<?php

declare(strict_types=1);

namespace App\Commands\Role\Delete;

use App\Commands\Command;
use App\Models\Role\Role;
use App\Support\Attributes\Handler\Handler;

#[Handler(\App\Commands\Role\Delete\DeleteHandler::class)]
final class DeleteCommand extends Command
{
    public function __construct(
        public readonly Role $role
    ) {}
}
