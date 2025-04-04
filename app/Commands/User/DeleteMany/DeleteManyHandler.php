<?php

declare(strict_types=1);

namespace App\Commands\User\DeleteMany;

use App\Commands\CommandBusInterface;
use App\Commands\Handler;
use App\Commands\User\Delete\DeleteCommand;
use Illuminate\Database\ConnectionInterface as DB;

final class DeleteManyHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function handle(DeleteManyCommand $command): int
    {
        /** @var int */
        return $this->db->transaction(function () use ($command): int {
            $deleted = 0;

            foreach ($command->users as $user) {
                $this->commandBus->execute(new DeleteCommand($user));

                $deleted++;
            }

            return $deleted;
        });
    }
}
