<?php

declare(strict_types=1);

namespace App\Commands\User\Edit;

use App\Commands\Handler;
use App\Models\Role\Role;
use App\Models\User\User;
use App\ValueObjects\Role\DefaultName;
use App\Commands\User\Edit\EditCommand;

final class EditHandler extends Handler
{
    public function handle(EditCommand $command): User
    {
        $this->db->beginTransaction();

        try {
            $user = $command->user->fill($command->toArray());

            if ($command->user->getOriginal('email') !== $command->email) {
                $user->email_verified_at = null;
            }

            $user->save();

            $user->syncRoles([
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
