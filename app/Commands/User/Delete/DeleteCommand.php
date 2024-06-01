<?php

declare(strict_types=1);

namespace App\Commands\User\Delete;

use App\Commands\Command;
use App\Models\User\User;
use App\Support\Attributes\Handler;

#[Handler(\App\Commands\User\Delete\DeleteHandler::class)]
final class DeleteCommand extends Command
{
    public function __construct(public readonly User $user)
    {
    }
}
