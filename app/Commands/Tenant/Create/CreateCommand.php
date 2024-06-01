<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Create;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Support\Attributes\Handler;
use Illuminate\Database\Eloquent\Collection;

#[Handler(\App\Commands\Tenant\Create\CreateHandler::class)]
final class CreateCommand extends Command
{
    public function __construct(
        public readonly string $name,
        public readonly User $user,
        public readonly Tenant $tenant = new Tenant(),
        public readonly Collection $users = new Collection()
    ) {
    }
}
