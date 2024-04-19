<?php

use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\Notification\NotificationChannel;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('Notification.{id}', NotificationChannel::class);
