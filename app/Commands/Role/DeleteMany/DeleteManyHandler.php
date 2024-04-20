<?php

declare(strict_types=1);

namespace App\Commands\Role\DeleteMany;

use App\Commands\Handler;
use App\Commands\Role\Delete\DeleteCommand;

final class DeleteManyHandler extends Handler
{
    public function handle(DeleteManyCommand $command): int
    {
        $this->db->beginTransaction();

        $deleted = 0;

        try {
            foreach ($command->roles as $role) {
                $this->commandBus->execute(new DeleteCommand($role));

                $deleted++;
            }
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        return $deleted;
    }
}
