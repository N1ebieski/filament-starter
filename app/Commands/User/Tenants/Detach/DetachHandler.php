<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\Detach;

use App\Commands\Handler;
use Illuminate\Database\ConnectionInterface as DB;

final class DetachHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
    ) {}

    public function handle(DetachCommand $command): bool
    {
        $this->db->transaction(fn (): int => $command->user->tenants()->detach($command->tenant));

        return true;
    }
}
