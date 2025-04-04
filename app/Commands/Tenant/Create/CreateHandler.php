<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Create;

use App\Commands\Handler;
use App\Models\Tenant\Tenant;
use Illuminate\Database\ConnectionInterface as DB;
use Spatie\Permission\PermissionRegistrar;

final class CreateHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
        private readonly PermissionRegistrar $permissionRegistrar
    ) {}

    public function handle(CreateCommand $command): Tenant
    {
        /** @var Tenant $tenant */
        $tenant = $this->db->transaction(function () use ($command): Tenant {
            $tenant = $command->tenant->newInstance(
                $command->only(...$command->tenant->getFillable())->toArray()
            );

            $tenant->user()->associate($command->user);

            $tenant->save();

            $tenant->users()->attach($command->users->push($command->user));

            $this->permissionRegistrar->setPermissionsTeamId($tenant->id);

            $command->user->givePermissionTo('tenant.*');

            return $tenant;
        });

        $tenant->refresh();

        return $tenant;
    }
}
