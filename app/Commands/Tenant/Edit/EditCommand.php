<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Edit;

use App\Commands\Command;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Support\Attributes\Handler\Handler;
use App\ValueObjects\Tenant\Name\Name;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Optional;

#[Handler(\App\Commands\Tenant\Edit\EditHandler::class)]
final class EditCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Tenant $tenant,
        public readonly Name|Optional $name = new Optional,
        public readonly User|Optional $user = new Optional,
        #[WithCast(CollectionOfModelsCast::class, User::class)]
        public readonly Collection|Optional $users = new Optional
    ) {}
}
