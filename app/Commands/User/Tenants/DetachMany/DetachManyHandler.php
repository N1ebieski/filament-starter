<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\DetachMany;

use App\Commands\CommandBusInterface;
use App\Commands\Handler;
use App\Commands\User\Tenants\Detach\DetachCommand;
use Illuminate\Database\ConnectionInterface as DB;

final class DetachManyHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
        private readonly CommandBusInterface $commandBus,
    ) {}

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
        } catch (\Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        }

        $this->db->commit();

        return $detached;
    }
}
