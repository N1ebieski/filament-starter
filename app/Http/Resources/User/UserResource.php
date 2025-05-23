<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Http\Resources\Resource;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\Tenant\TenantResource;
use App\ValueObjects\User\Name\Name;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class UserResource extends Resource
{
    public function __construct(
        public readonly int $id,
        public readonly Optional|Name $name = new Optional,
        public readonly Optional|StatusEmail $statusEmail = new Optional,
        public readonly Optional|DateTime|null $createdAt = new Optional,
        public readonly Optional|DateTime|null $updatedAt = new Optional,
        #[DataCollectionOf(RoleResource::class)]
        public readonly Optional|Collection $roles = new Optional,
        #[DataCollectionOf(TenantResource::class)]
        public readonly Optional|Collection $tenants = new Optional,
    ) {}
}
