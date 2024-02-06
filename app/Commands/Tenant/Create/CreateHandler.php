<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Create;

use App\Commands\Handler;
use App\Models\Tenant\Tenant;
use App\Commands\Tenant\Create\CreateCommand;

final class CreateHandler extends Handler
{
    public function handle(CreateCommand $command): Tenant
    {
        $this->db->beginTransaction();

        try {
            $tenant = $command->tenant->newInstance($command->toArray());

            $tenant->user()->associate($command->user);

            $tenant->save();

            $tenant->users()->attach($command->users->push($command->user));
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        /** @var Tenant */
        return $tenant->fresh();
    }
}
