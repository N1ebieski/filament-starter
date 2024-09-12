<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\Detach;

use App\Commands\Handler;

final class DetachHandler extends Handler
{
    public function handle(DetachCommand $command): bool
    {
        $this->db->beginTransaction();

        try {
            $user = $command->user;

            $user->tenants()->detach($command->tenant);
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        return true;
    }
}
