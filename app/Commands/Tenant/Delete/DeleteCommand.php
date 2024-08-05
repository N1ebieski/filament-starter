<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Delete;

use App\Commands\Command;
use App\Models\Tenant\Tenant;
use App\Data\Casts\Model\ModelCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;

#[Handler(\App\Commands\Tenant\Delete\DeleteHandler::class)]
final class DeleteCommand extends Command
{
    public function __construct(
        #[WithCast(ModelCast::class, Tenant::class)]
        public readonly Tenant $tenant
    ) {
    }
}
