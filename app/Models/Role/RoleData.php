<?php

declare(strict_types=1);

namespace App\Models\Role;

use App\Data\Data\Data;
use App\Overrides\Spatie\LaravelData\Lazy;
use App\ValueObjects\Role\Name\Name;
use DateTime;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Lazy as BaseLazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * @property-read int $id
 * @property-read Name $name
 * @property-read DateTime|null $createdAt
 * @property-read DateTime|null $updatedAt
 */
#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class RoleData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly BaseLazy|Name $name,
        public readonly BaseLazy|DateTime|null $createdAt,
        public readonly BaseLazy|DateTime|null $updatedAt,
    ) {}

    public static function prepareFromModel(Role $role, array $properties): array
    {
        $properties = [
            ...$properties,
            'name' => Lazy::whenLoaded('name', $role, fn () => $role->name),
            'created_at' => Lazy::whenLoaded('created_at', $role, fn () => $role->createdAt),
            'updated_at' => Lazy::whenLoaded('updated_at', $role, fn () => $role->updatedAt),
        ];

        return $properties;
    }
}
