<?php

declare(strict_types=1);

namespace App\Commands\User\Delete;

use App\Commands\Command;
use App\Models\User\User;
use App\Data\Casts\Model\ModelCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;

#[Handler(\App\Commands\User\Delete\DeleteHandler::class)]
final class DeleteCommand extends Command
{
    public function __construct(
        #[WithCast(ModelCast::class, User::class)]
        public readonly User $user
    ) {
    }
}
