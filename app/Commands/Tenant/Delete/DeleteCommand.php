<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Delete;

use App\Commands\Command;
use App\Models\Tenant\Tenant;
use App\Support\Attributes\Handler;

#[Handler(\App\Commands\Tenant\Delete\DeleteHandler::class)]
final class DeleteCommand extends Command
{
    public function __construct(public readonly Tenant $tenant)
    {
    }
}
