<?php

declare(strict_types=1);

namespace App\Models\Permission;

use App\Models\ModelData;
use App\ValueObjects\Permission\Name\Name;
use DateTime;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

/**
 * @property-read int $id
 * @property-read Name $name
 * @property-read DateTime|null $createdAt
 * @property-read DateTime|null $updatedAt
 */
#[MapName(SnakeCaseMapper::class)]
final class PermissionData extends ModelData
{
    public function __construct(
        public readonly int $id,
        public readonly Optional|Name $name = new Optional,
        public readonly Optional|DateTime|null $createdAt = new Optional,
        public readonly Optional|DateTime|null $updatedAt = new Optional,
    ) {}
}
