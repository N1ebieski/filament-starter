<?php

declare(strict_types=1);

namespace App\Commands\Role\Edit;

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
use Spatie\LaravelData\Optional;

#[Handler(\App\Commands\Role\Edit\EditHandler::class)]
final class EditCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Role $role,
        #[WithCast(ValueObjectCast::class, Name::class)]
        #[WithTransformer(ValueObjectTransformer::class)]
        public readonly Name|Optional $name = new Optional,
        #[WithCast(CollectionOfModelsCast::class, Permission::class)]
        public readonly Collection|Optional $permissions = new Optional
    ) {}
}
