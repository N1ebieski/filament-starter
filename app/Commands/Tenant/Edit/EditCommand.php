<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Edit;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use Spatie\LaravelData\Optional;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use App\Data\ObjectDefaultsInterface;
use Spatie\LaravelData\Attributes\WithCast;
use App\Data\Casts\ModelCollectionOf\ModelCollectionOfCast;

#[Handler(\App\Commands\Tenant\Edit\EditHandler::class)]
final class EditCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Tenant $tenant,
        public readonly string|Optional $name = new Optional(),
        public readonly User|Optional $user = new Optional(),
        #[WithCast(ModelCollectionOfCast::class, User::class)]
        public readonly Collection|Optional $users = new Optional()
    ) {
    }
}
