<?php

declare(strict_types=1);

namespace App\Broadcasting\Notification;

use App\Models\User\User;

final class NotificationChannel
{
    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, int $userId): bool
    {
        return $user->id === $userId;
    }
}
