<?php

declare(strict_types=1);

namespace App\Commands\User\Create;

use App\Commands\Command;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Models\Role\Role;
use App\Models\User\User;
use App\Support\Attributes\Handler\Handler;
use App\ValueObjects\User\Email\Email;
use App\ValueObjects\User\Name\Name;
use Illuminate\Support\Collection;
use SensitiveParameter;
use Spatie\LaravelData\Attributes\WithCast;

#[Handler(\App\Commands\User\Create\CreateHandler::class)]
final class CreateCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Name $name,
        #[SensitiveParameter]
        public readonly Email $email,
        #[SensitiveParameter]
        public readonly string $password,
        public readonly User $user = new User,
        #[WithCast(CollectionOfModelsCast::class, Role::class)]
        public readonly Collection $roles = new Collection
    ) {}
}
