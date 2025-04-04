<?php

declare(strict_types=1);

namespace App\Commands\User\Delete;

use App\Commands\Handler;
use Illuminate\Database\ConnectionInterface as DB;

final class DeleteHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
    ) {}

    public function handle(DeleteCommand $command): ?bool
    {
        return $this->db->transaction(fn (): ?bool => $command->user->delete());
    }
}
