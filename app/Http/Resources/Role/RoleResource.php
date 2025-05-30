<?php

declare(strict_types=1);

namespace App\Http\Resources\Role;

use App\Http\Resources\Resource;
use App\ValueObjects\Role\Name\Name;
use DateTime;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class RoleResource extends Resource
{
    public function __construct(
        public readonly int $id,
        public readonly Optional|Name $name = new Optional,
        public readonly Optional|DateTime|null $createdAt = new Optional,
        public readonly Optional|DateTime|null $updatedAt = new Optional
    ) {}
}
