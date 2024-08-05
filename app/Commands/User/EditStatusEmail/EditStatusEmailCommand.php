<?php

declare(strict_types=1);

namespace App\Commands\User\EditStatusEmail;

use App\Commands\Command;
use App\Models\User\User;
use App\Data\Casts\Model\ModelCast;
use Spatie\LaravelData\Casts\EnumCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use App\ValueObjects\User\StatusEmail\StatusEmail;

#[Handler(\App\Commands\User\EditStatusEmail\EditStatusEmailHandler::class)]
final class EditStatusEmailCommand extends Command
{
    public function __construct(
        #[WithCast(ModelCast::class, User::class)]
        public readonly User $user,
        #[WithCast(EnumCast::class)]
        public readonly StatusEmail $status
    ) {
    }
}
