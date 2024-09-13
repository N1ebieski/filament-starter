<?php

declare(strict_types=1);

namespace App\Commands\User\EditStatusEmail;

use App\Commands\Handler;
use App\Models\User\User;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use Illuminate\Database\ConnectionInterface as DB;
use App\Commands\User\EditStatusEmail\EditStatusEmailCommand;

final class EditStatusEmailHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
        private readonly Carbon $carbon
    ) {
    }

    public function handle(EditStatusEmailCommand $command): User
    {
        $this->db->beginTransaction();

        try {
            $command->user->update([
                'email_verified_at' => $command->status->isEquals(StatusEmail::Verified) ?
                    $this->carbon->now() : null
            ]);

            if (
                $command->status->isEquals(StatusEmail::Unverified)
                && $command->user instanceof MustVerifyEmail
            ) {
                $command->user->sendEmailVerificationNotification();
            }
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        /** @var User */
        return $command->user->fresh();
    }
}
