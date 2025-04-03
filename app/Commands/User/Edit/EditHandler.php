<?php

declare(strict_types=1);

namespace App\Commands\User\Edit;

use App\Commands\Handler;
use App\Models\Role\Role;
use App\Models\User\User;
use App\ValueObjects\Role\Name\DefaultName;
use Illuminate\Database\ConnectionInterface as DB;
use Spatie\LaravelData\Optional;

final readonly class EditHandler extends Handler
{
    public function __construct(
        private DB $db,
    ) {}

    public function handle(EditCommand $command): User
    {
        $this->db->beginTransaction();

        try {
            $user = $command->user->fill(
                $command->only(...$command->user->getFillable())->toArray()
            );

            if (
                ! ($command->email instanceof Optional)
                && $command->user->getOriginal('email') !== $command->email
            ) {
                $user->setAttribute('email_verified_at', null);
            }

            $user->save();

            if (! ($command->roles instanceof Optional)) {
                $user->syncRoles([
                    DefaultName::User->value,
                    ...$command->roles->map(fn (Role $role) => $role->name->value)->toArray(),
                ]);
            }
        } catch (\Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        }

        $this->db->commit();

        /** @var User */
        return $user->fresh();
    }
}
