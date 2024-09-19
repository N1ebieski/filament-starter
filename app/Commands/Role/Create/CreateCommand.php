<?php

declare(strict_types=1);

namespace App\Commands\Role\Create;

use App\Commands\Command;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Models\Permission\Permission;
use App\Models\Role\Role;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCast;

#[Handler(\App\Commands\Role\Create\CreateHandler::class)]
final class CreateCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly string $name,
        public readonly Role $role = new Role,
        #[WithCast(CollectionOfModelsCast::class, Permission::class)]
        public readonly Collection $permissions = new Collection
    ) {}
}
