<?php

declare(strict_types=1);

namespace App\Http\Resources\Tenant;

use App\Http\Resources\Resource;
use App\Http\Resources\User\UserResource;
use App\ValueObjects\Tenant\Name\Name;
use DateTime;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class TenantResource extends Resource
{
    public function __construct(
        public readonly int $id,
        public readonly Optional|Name $name = new Optional,
        public readonly Optional|DateTime|null $createdAt = new Optional,
        public readonly Optional|DateTime|null $updatedAt = new Optional,
        public readonly Optional|UserResource $user = new Optional
    ) {}
}
