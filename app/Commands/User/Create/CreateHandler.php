<?php

declare(strict_types=1);

namespace App\Commands\User\Create;

use App\Commands\Handler;
use App\Models\Role\Role;
use App\Models\User\User;
use App\ValueObjects\Role\Name\DefaultName;
use App\Commands\User\Create\CreateCommand;

final class CreateHandler extends Handler
{
    public function handle(CreateCommand $command): User
    {
        $this->db->beginTransaction();

        try {
            $user = $command->user->newInstance(
                $command->only($command->user->getFillable())
            );

            $user->save();

            $user->assignRole([
                DefaultName::User->value,
                ...$command->roles->map(fn (Role $role) => $role->name->value)->toArray()
            ]);
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        /** @var User */
        return $user->fresh();
    }
}
