<?php

declare(strict_types=1);

namespace App\Commands\Role\Create;

use App\Commands\Command;
use App\Models\Role\Role;
use App\Models\Permission\Permission;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use App\Data\ObjectDefaultsInterface;
use Spatie\LaravelData\Attributes\WithCast;
use App\Data\Casts\ModelCollectionOf\ModelCollectionOfCast;

#[Handler(\App\Commands\Role\Create\CreateHandler::class)]
final class CreateCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly string $name,
        public readonly Role $role = new Role(),
        #[WithCast(ModelCollectionOfCast::class, Permission::class)]
        public readonly Collection $permissions = new Collection()
    ) {
    }
}
