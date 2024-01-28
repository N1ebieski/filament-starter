<?php

declare(strict_types=1);

namespace App\Commands\User\EditStatusEmail;

use App\Commands\Command;
use App\Models\User\User;
use App\ValueObjects\User\StatusEmail;

final class EditStatusEmailCommand extends Command
{
    public function __construct(
        public readonly User $user,
        public readonly StatusEmail $status
    ) {
    }
}
