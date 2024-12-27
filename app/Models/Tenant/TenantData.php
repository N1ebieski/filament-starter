<?php

declare(strict_types=1);

namespace App\Models\Tenant;

use App\Data\Data\Data;
use App\Data\Pipelines\ModelDataPipe\PrepareFromModelInterface;
use App\Models\User\UserData;
use App\Overrides\Spatie\LaravelData\Lazy;
use App\ValueObjects\Tenant\Name\Name;
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
 * @property-read UserData|null $user
 */
#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class TenantData extends Data implements PrepareFromModelInterface
{
    public function __construct(
        public readonly int $id,
        public readonly BaseLazy|Name $name,
        public readonly BaseLazy|DateTime|null $createdAt,
        public readonly BaseLazy|DateTime|null $updatedAt,
        public readonly BaseLazy|UserData|null $user
    ) {}

    public static function prepareFromModel(Tenant $tenant, array $properties): array
    {
        $properties = [
            ...$properties,
            'name' => Lazy::whenLoaded('name', $tenant, fn () => $tenant->name),
            'created_at' => Lazy::whenLoaded('created_at', $tenant, fn () => $tenant->createdAt),
            'updated_at' => Lazy::whenLoaded('updated_at', $tenant, fn () => $tenant->updatedAt),
            'user' => Lazy::whenLoaded('user', $tenant, fn () => UserData::from($tenant->user)),
        ];

        return $properties;
    }
}
