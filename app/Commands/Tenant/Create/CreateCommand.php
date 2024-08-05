<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Create;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Data\Casts\Model\ModelCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;

#[Handler(\App\Commands\Tenant\Create\CreateHandler::class)]
final class CreateCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly string $name,
        #[WithCast(ModelCast::class, User::class)]
        public readonly User $user,
        public readonly Tenant $tenant = new Tenant(),
        #[WithCast(CollectionOfModelsCast::class, User::class)]
        public readonly Collection $users = new Collection()
    ) {
    }
}
