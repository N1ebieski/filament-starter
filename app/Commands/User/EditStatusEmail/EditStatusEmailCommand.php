<?php

declare(strict_types=1);

namespace App\Commands\User\EditStatusEmail;

use App\Commands\Command;
use App\Models\User\User;
use App\Support\Attributes\Handler\Handler;
use App\ValueObjects\User\StatusEmail\StatusEmail;

#[Handler(\App\Commands\User\EditStatusEmail\EditStatusEmailHandler::class)]
final class EditStatusEmailCommand extends Command
{
    public function __construct(
        public readonly User $user,
        public readonly StatusEmail $status
    ) {}
}
