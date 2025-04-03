<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\Detach;

use App\Commands\Handler;
use Illuminate\Database\ConnectionInterface as DB;

final class DetachHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
    ) {}

    public function handle(DetachCommand $command): bool
    {
        $this->db->beginTransaction();

        try {
            $user = $command->user;

            $user->tenants()->detach($command->tenant);
        } catch (\Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        }

        $this->db->commit();

        return true;
    }
}
