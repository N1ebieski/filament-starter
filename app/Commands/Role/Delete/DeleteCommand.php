<?php

declare(strict_types=1);

namespace App\Commands\Role\Delete;

use App\Commands\Command;
use App\Data\Casts\Model\ModelCast;
use App\Models\Role\Role;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;

#[Handler(\App\Commands\Role\Delete\DeleteHandler::class)]
final class DeleteCommand extends Command
{
    public function __construct(
        #[WithCast(ModelCast::class, Role::class)]
        public readonly Role $role
    ) {}
}
