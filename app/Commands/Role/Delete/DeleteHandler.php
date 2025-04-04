<?php

declare(strict_types=1);

namespace App\Commands\Role\Delete;

use App\Commands\Handler;
use Illuminate\Database\ConnectionInterface as DB;

final class DeleteHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
    ) {}

    public function handle(DeleteCommand $command): bool
    {
        $this->db->transaction(fn (): int => $command->role->delete());

        return true;
    }
}
