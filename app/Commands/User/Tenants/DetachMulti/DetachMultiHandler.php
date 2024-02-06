<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\DetachMulti;

use App\Commands\Handler;
use App\Commands\User\Delete\DeleteCommand;
use App\Commands\User\Tenants\Detach\DetachCommand;

final class DetachMultiHandler extends Handler
{
    public function handle(DetachMultiCommand $command): int
    {
        $this->db->beginTransaction();

        $affected = 0;

        try {
            foreach ($command->users as $user) {
                $this->commandBus->execute(new DetachCommand(
                    tenant: $command->tenant,
                    user: $user
                ));

                $affected++;
            }
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        return $affected;
    }
}
