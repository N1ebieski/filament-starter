<?php

declare(strict_types=1);

namespace App\Commands\User\Create;

use App\Commands\Command;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Casts\ValueObject\ValueObjectCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Data\Transformers\ValueObject\ValueObjectTransformer;
use App\Models\Role\Role;
use App\Models\User\User;
use App\Support\Attributes\Handler\Handler;
use App\ValueObjects\User\Email\Email;
use App\ValueObjects\User\Name\Name;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;

#[Handler(\App\Commands\User\Create\CreateHandler::class)]
final class CreateCommand extends Command implements ObjectDefaultsInterface
{
    public function __construct(
        #[WithCast(ValueObjectCast::class, Name::class)]
        #[WithTransformer(ValueObjectTransformer::class)]
        public readonly Name $name,
        #[WithCast(ValueObjectCast::class, Email::class)]
        #[WithTransformer(ValueObjectTransformer::class)]
        public readonly Email $email,
        public readonly string $password,
        public readonly User $user = new User,
        #[WithCast(CollectionOfModelsCast::class, Role::class)]
        public readonly Collection $roles = new Collection
    ) {}
}
