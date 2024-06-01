<?php

declare(strict_types=1);

namespace App\Commands\User\DeleteMany;

use App\Commands\Command;
use App\Models\User\User;
use App\Support\Attributes\Handler;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read Collection<User> $users
 */
#[Handler(\App\Commands\User\DeleteMany\DeleteManyHandler::class)]
final class DeleteManyCommand extends Command
{
    public function __construct(
        public readonly Collection $users
    ) {
    }
}
