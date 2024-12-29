<?php

declare(strict_types=1);

namespace App\Models\Tenant;

use App\Models\ModelData;
use App\Models\User\UserData;
use App\ValueObjects\Tenant\Name\Name;
use DateTime;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

/**
 * @property-read int $id
 * @property-read Name $name
 * @property-read DateTime|null $createdAt
 * @property-read DateTime|null $updatedAt
 * @property-read UserData|null $user
 */
#[MapName(SnakeCaseMapper::class)]
final class TenantData extends ModelData
{
    public function __construct(
        public readonly int $id,
        public readonly Optional|Name $name = new Optional,
        public readonly Optional|DateTime|null $createdAt = new Optional,
        public readonly Optional|DateTime|null $updatedAt = new Optional,
        public readonly Optional|UserData|null $user = new Optional
    ) {}
}
