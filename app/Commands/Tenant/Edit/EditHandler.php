<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Edit;

use App\Commands\Handler;
use App\Models\Tenant\Tenant;

final class EditHandler extends Handler
{
    public function handle(EditCommand $command): Tenant
    {
        $this->db->beginTransaction();

        try {
            $tenant = $command->tenant->fill($command->toArray());

            $tenant->user()->associate($command->user);

            $tenant->save();

            $tenant->users()->sync($command->users);
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        /** @var Tenant */
        return $tenant->fresh();
    }
}
