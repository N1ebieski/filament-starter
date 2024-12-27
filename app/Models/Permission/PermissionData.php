<?php

declare(strict_types=1);

namespace App\Models\Permission;

use App\Data\Data\Data;
use App\Overrides\Spatie\LaravelData\Lazy;
use App\ValueObjects\Permission\Name\Name;
use DateTime;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Lazy as BaseLazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * @property-read int $id
 * @property-read Name $name
 * @property-read DateTime|null $createdAt
 * @property-read DateTime|null $updatedAt
 */
#[MapName(SnakeCaseMapper::class)]
final class PermissionData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly BaseLazy|Name $name,
        public readonly BaseLazy|DateTime|null $createdAt,
        public readonly BaseLazy|DateTime|null $updatedAt,
    ) {}

    public static function prepareFromModel(Permission $permission, array $properties): array
    {
        $properties = [
            ...$properties,
            'name' => Lazy::whenLoaded('name', $permission, fn () => $permission->name),
            'created_at' => Lazy::whenLoaded('created_at', $permission, fn () => $permission->createdAt),
            'updated_at' => Lazy::whenLoaded('updated_at', $permission, fn () => $permission->updatedAt),
        ];

        return $properties;
    }
}
