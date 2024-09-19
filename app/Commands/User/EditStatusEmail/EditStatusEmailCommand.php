<?php

declare(strict_types=1);

namespace App\Commands\User\EditStatusEmail;

use App\Commands\Command;
use App\Data\Casts\Model\ModelCast;
use App\Models\User\User;
use App\Support\Attributes\Handler\Handler;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

#[Handler(\App\Commands\User\EditStatusEmail\EditStatusEmailHandler::class)]
final class EditStatusEmailCommand extends Command
{
    public function __construct(
        #[WithCast(ModelCast::class, User::class)]
        public readonly User $user,
        #[WithCast(EnumCast::class)]
        public readonly StatusEmail $status
    ) {}
}
