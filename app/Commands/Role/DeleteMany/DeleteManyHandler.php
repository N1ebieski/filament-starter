<?php

declare(strict_types=1);

namespace App\Commands\Role\DeleteMany;

use App\Commands\CommandBusInterface;
use App\Commands\Handler;
use App\Commands\Role\Delete\DeleteCommand;
use Illuminate\Database\ConnectionInterface as DB;

final class DeleteManyHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function handle(DeleteManyCommand $command): int
    {
        $this->db->beginTransaction();

        $deleted = 0;

        try {
            foreach ($command->roles as $role) {
                $this->commandBus->execute(new DeleteCommand($role));

                $deleted++;
            }
        } catch (\Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        }

        $this->db->commit();

        return $deleted;
    }
}
