<?php

declare(strict_types=1);

namespace App\Commands\User\DeleteMany;

use App\Commands\Command;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Models\User\User;
use App\Support\Attributes\Handler\Handler;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * @property-read Collection<User> $users
 */
#[Handler(\App\Commands\User\DeleteMany\DeleteManyHandler::class)]
final class DeleteManyCommand extends Command
{
    public function __construct(
        #[WithCast(CollectionOfModelsCast::class, User::class)]
        public readonly Collection $users
    ) {}
}
