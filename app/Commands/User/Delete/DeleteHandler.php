<?php

declare(strict_types=1);

namespace App\Commands\User\Delete;

use App\Commands\Handler;
use App\Commands\User\Delete\DeleteCommand;

final class DeleteHandler extends Handler
{
    public function handle(DeleteCommand $command): bool
    {
        $this->db->beginTransaction();

        try {
            $command->user->delete();
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        return true;
    }
}
