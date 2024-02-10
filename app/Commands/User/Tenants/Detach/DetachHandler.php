<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\Detach;

use App\Commands\Handler;
use App\Models\User\User;

final class DetachHandler extends Handler
{
    public function handle(DetachCommand $command): User
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

        /** @var User */
        return $user->fresh();
    }
}
