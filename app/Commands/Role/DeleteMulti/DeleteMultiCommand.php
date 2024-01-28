<?php

declare(strict_types=1);

namespace App\Commands\Role\DeleteMulti;

use App\Commands\Command;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read Collection<Role> $roles
 */
final class DeleteMultiCommand extends Command
{
    public function __construct(
        public readonly Collection $roles
    ) {
    }
}
