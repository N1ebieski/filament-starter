<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\DetachMany;

use App\Commands\Handler;
use App\Commands\CommandBusInterface;
use Illuminate\Database\ConnectionInterface as DB;
use App\Commands\User\Tenants\Detach\DetachCommand;

final class DetachManyHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function handle(DetachManyCommand $command): int
    {
        $this->db->beginTransaction();

        $detached = 0;

        try {
            foreach ($command->users as $user) {
                $this->commandBus->execute(new DetachCommand(
                    tenant: $command->tenant,
                    user: $user
                ));

                $detached++;
            }
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        return $detached;
    }
}
