<?php

declare(strict_types=1);

namespace App\Commands\User\DeleteMulti;

use App\Commands\Handler;
use App\Commands\User\Delete\DeleteCommand;

final class DeleteMultiHandler extends Handler
{
    public function handle(DeleteMultiCommand $command): int
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
