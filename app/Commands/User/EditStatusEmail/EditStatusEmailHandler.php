<?php

declare(strict_types=1);

namespace App\Commands\User\EditStatusEmail;

use App\Commands\Handler;
use App\Models\User\User;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\ConnectionInterface as DB;
use Illuminate\Support\Carbon;

final class EditStatusEmailHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
        private readonly Carbon $carbon
    ) {}

    public function handle(EditStatusEmailCommand $command): User
    {
        /** @var User $user */
        $user = $this->db->transaction(function () use ($command): User {
            $command->user->update([
                'email_verified_at' => $command->status->isEquals(StatusEmail::Verified) ?
                    $this->carbon->now() : null,
            ]);

            if (
                $command->status->isEquals(StatusEmail::Unverified)
                && $command->user instanceof MustVerifyEmail // @phpstan-ignore-line
            ) {
                $command->user->sendEmailVerificationNotification();
            }

            return $command->user;
        });

        $user->refresh();

        return $user;
    }
}
