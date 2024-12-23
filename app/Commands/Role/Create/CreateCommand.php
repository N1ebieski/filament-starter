<?php

declare(strict_types=1);

namespace App\Commands\Role\Create;

use App\Commands\Command;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Casts\ValueObject\ValueObjectCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Data\Transformers\ValueObject\ValueObjectTransformer;
use App\Models\Permission\Permission;
use App\Models\Role\Role;
use App\Support\Attributes\Handler\Handler;
use App\ValueObjects\Role\Name\Name;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;

#[Handler(\App\Commands\Role\Create\CreateHandler::class)]
final class CreateCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        #[WithCast(ValueObjectCast::class, Name::class)]
        #[WithTransformer(ValueObjectTransformer::class)]
        public readonly Name $name,
        public readonly Role $role = new Role,
        #[WithCast(CollectionOfModelsCast::class, Permission::class)]
        public readonly Collection $permissions = new Collection
    ) {}
}
