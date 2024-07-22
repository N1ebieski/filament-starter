<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Create;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use App\Support\Data\ObjectDefaultsInterface;
use Spatie\LaravelData\Attributes\WithCastable;
use App\Data\Casts\ModelCollectionOf\ModelCollectionOf;

#[Handler(\App\Commands\Tenant\Create\CreateHandler::class)]
final class CreateCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly string $name,
        public readonly User $user,
        public readonly Tenant $tenant = new Tenant(),
        #[WithCastable(ModelCollectionOf::class, User::class)]
        public readonly Collection $users = new Collection()
    ) {
    }
}
