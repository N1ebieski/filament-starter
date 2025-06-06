<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Create;

use App\Commands\Command;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Support\Attributes\Handler\Handler;
use App\ValueObjects\Tenant\Name\Name;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCast;

#[Handler(\App\Commands\Tenant\Create\CreateHandler::class)]
final class CreateCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Name $name,
        public readonly User $user,
        public readonly Tenant $tenant = new Tenant,
        #[WithCast(CollectionOfModelsCast::class, User::class)]
        public readonly Collection $users = new Collection
    ) {}
}
