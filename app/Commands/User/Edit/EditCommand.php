<?php

declare(strict_types=1);

namespace App\Commands\User\Edit;

use App\Commands\Command;
use App\Models\Role\Role;
use App\Models\User\User;
use Spatie\LaravelData\Optional;
use App\Data\Casts\Model\ModelCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;

#[Handler(\App\Commands\User\Edit\EditHandler::class)]
final class EditCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        #[WithCast(ModelCast::class, User::class)]
        public readonly User $user,
        public readonly string|Optional $name = new Optional(),
        public readonly string|Optional $email = new Optional(),
        public readonly string|Optional $password = new Optional(),
        #[WithCast(CollectionOfModelsCast::class, Role::class)]
        public readonly Collection|Optional $roles = new Optional()
    ) {
    }
}
