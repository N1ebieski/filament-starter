<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Edit;

use App\Commands\Command;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use Spatie\LaravelData\Optional;
use App\Data\Casts\Model\ModelCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;

#[Handler(\App\Commands\Tenant\Edit\EditHandler::class)]
final class EditCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        #[WithCast(ModelCast::class, Tenant::class)]
        public readonly Tenant $tenant,
        public readonly string|Optional $name = new Optional(),
        #[WithCast(ModelCast::class, User::class)]
        public readonly User|Optional $user = new Optional(),
        #[WithCast(CollectionOfModelsCast::class, User::class)]
        public readonly Collection|Optional $users = new Optional()
    ) {
    }
}
