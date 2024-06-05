<?php

declare(strict_types=1);

namespace App\Broadcasting\Notification;

use App\Models\User\User;
use App\Broadcasting\Channel;

final class NotificationChannel extends Channel
{
    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, int $userId): bool
    {
        return $user->id === $userId;
    }
}
