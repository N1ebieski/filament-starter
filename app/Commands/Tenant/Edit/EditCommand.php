<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Edit;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Support\Attributes\Handler;
use Illuminate\Database\Eloquent\Collection;

#[Handler(\App\Commands\Tenant\Edit\EditHandler::class)]
final class EditCommand extends Command
{
    public function __construct(
        public readonly string $name,
        public readonly User $user,
        public readonly Tenant $tenant,
        public readonly Collection $users
    ) {
    }
}
