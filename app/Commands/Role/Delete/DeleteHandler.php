<?php

declare(strict_types=1);

namespace App\Commands\Role\Delete;

use App\Commands\Handler;
use Illuminate\Database\ConnectionInterface as DB;

final readonly class DeleteHandler extends Handler
{
    public function __construct(
        private DB $db,
    ) {}

    public function handle(DeleteCommand $command): bool
    {
        $this->db->beginTransaction();

        try {
            $command->role->delete();
        } catch (\Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        }

        $this->db->commit();

        return true;
    }
}
