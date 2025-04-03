<?php

declare(strict_types=1);

namespace App\Commands\User\Create;

use App\Commands\Handler;
use App\Models\Role\Role;
use App\Models\User\User;
use App\ValueObjects\Role\Name\DefaultName;
use Illuminate\Database\ConnectionInterface as DB;

final readonly class CreateHandler extends Handler
{
    public function __construct(
        private DB $db,
    ) {}

    public function handle(CreateCommand $command): User
    {
        $this->db->beginTransaction();

        try {
            $user = $command->user->newInstance(
                $command->only(...$command->user->getFillable())->toArray()
            );

            $user->save();

            $user->assignRole([
                DefaultName::User->value,
                ...$command->roles->map(fn (Role $role) => $role->name->value)->toArray(),
            ]);
        } catch (\Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        }

        $this->db->commit();

        /** @var User */
        return $user->fresh();
    }
}
