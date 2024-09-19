<?php

declare(strict_types=1);

namespace App\Commands\User\Edit;

use App\Commands\Command;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Casts\Model\ModelCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Models\Role\Role;
use App\Models\User\User;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Optional;

#[Handler(\App\Commands\User\Edit\EditHandler::class)]
final class EditCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        #[WithCast(ModelCast::class, User::class)]
        public readonly User $user,
        public readonly string|Optional $name = new Optional,
        public readonly string|Optional $email = new Optional,
        public readonly string|Optional $password = new Optional,
        #[WithCast(CollectionOfModelsCast::class, Role::class)]
        public readonly Collection|Optional $roles = new Optional
    ) {}
}
