<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Delete;

use App\Commands\Handler;
use App\Commands\Tenant\Delete\DeleteCommand;

final class DeleteHandler extends Handler
{
    public function handle(DeleteCommand $command): bool
    {
        $this->db->beginTransaction();

        try {
            $command->tenant->delete();
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        return true;
    }
}
