<?php

declare(strict_types=1);

namespace App\Commands\Role\Edit;

use App\Commands\Command;
use App\Models\Role\Role;
use Spatie\LaravelData\Optional;
use App\Models\Permission\Permission;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use App\Data\ObjectDefaultsInterface;
use Spatie\LaravelData\Attributes\WithCast;
use App\Data\Casts\ModelCollectionOf\ModelCollectionOfCast;

#[Handler(\App\Commands\Role\Edit\EditHandler::class)]
final class EditCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Role $role,
        public readonly string|Optional $name = new Optional(),
        #[WithCast(ModelCollectionOfCast::class, Permission::class)]
        public readonly Collection|Optional $permissions = new Optional()
    ) {
    }
}
