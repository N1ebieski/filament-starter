<?php

declare(strict_types=1);

namespace App\Commands\Role\Edit;

use App\Commands\Command;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Models\Permission\Permission;
use App\Models\Role\Role;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Optional;

#[Handler(\App\Commands\Role\Edit\EditHandler::class)]
final class EditCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Role $role,
        public readonly string|Optional $name = new Optional,
        #[WithCast(CollectionOfModelsCast::class, Permission::class)]
        public readonly Collection|Optional $permissions = new Optional
    ) {}
}
