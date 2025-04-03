<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\Attach;

use App\Commands\Handler;
use App\Models\Permission\Permission;
use Illuminate\Database\ConnectionInterface as DB;

final readonly class AttachHandler extends Handler
{
    public function __construct(
        private DB $db,
    ) {}

    public function handle(AttachCommand $command): bool
    {
        $this->db->beginTransaction();

        try {
            $user = $command->user;

            $user->tenants()->attach($command->tenant);

            $user->givePermissionTo(
                $command->permissions
                    ->map(fn (Permission $permission): string => $permission->name->value)
                    ->toArray()
            );
        } catch (\Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        }

        $this->db->commit();

        return true;
    }
}
