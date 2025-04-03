<?php

declare(strict_types=1);

namespace App\Commands\User\DeleteMany;

use App\Commands\CommandBusInterface;
use App\Commands\Handler;
use App\Commands\User\Delete\DeleteCommand;
use Illuminate\Database\ConnectionInterface as DB;

final readonly class DeleteManyHandler extends Handler
{
    public function __construct(
        private DB $db,
        private CommandBusInterface $commandBus,
    ) {}

    public function handle(DeleteManyCommand $command): int
    {
        $this->db->beginTransaction();

        $deleted = 0;

        try {
            foreach ($command->users as $user) {
                $this->commandBus->execute(new DeleteCommand($user));

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
