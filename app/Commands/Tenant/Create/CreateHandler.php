<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Create;

use App\Commands\Handler;
use App\Models\Tenant\Tenant;
use Illuminate\Database\ConnectionInterface as DB;
use Spatie\Permission\PermissionRegistrar;

final readonly class CreateHandler extends Handler
{
    public function __construct(
        private DB $db,
        private PermissionRegistrar $permissionRegistrar
    ) {}

    public function handle(CreateCommand $command): Tenant
    {
        $this->db->beginTransaction();

        try {
            $tenant = $command->tenant->newInstance(
                $command->only(...$command->tenant->getFillable())->toArray()
            );

            $tenant->user()->associate($command->user);

            $tenant->save();

            $tenant->users()->attach($command->users->push($command->user));

            $this->permissionRegistrar->setPermissionsTeamId($tenant->id);

            $command->user->givePermissionTo('tenant.*');
        } catch (\Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        }

        $this->db->commit();

        /** @var Tenant */
        return $tenant->fresh();
    }
}
