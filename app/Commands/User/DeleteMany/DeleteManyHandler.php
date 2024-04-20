<?php

declare(strict_types=1);

namespace App\Commands\User\DeleteMany;

use App\Commands\Handler;
use App\Commands\User\Delete\DeleteCommand;

final class DeleteManyHandler extends Handler
{
    public function handle(DeleteManyCommand $command): int
    {
        $this->db->beginTransaction();

        $deleted = 0;

        try {
            foreach ($command->users as $user) {
                $this->commandBus->execute(new DeleteCommand($user));

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
