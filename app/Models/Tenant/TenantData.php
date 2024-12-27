<?php

declare(strict_types=1);

namespace App\Models\Tenant;

use App\Data\Data\Data;
use App\Models\User\UserData;
use App\Overrides\Spatie\LaravelData\Lazy;
use App\ValueObjects\Tenant\Name\Name;
use DateTime;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Lazy as BaseLazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * @property-read UserData|null $user
 */
#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class TenantData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly Name $name,
        public readonly ?DateTime $createdAt,
        public readonly ?DateTime $updatedAt,
        public readonly BaseLazy|UserData|null $user
    ) {}

    public static function prepareFromModel(Tenant $tenant, array $properties): array
    {
        $properties = [
            ...$properties,
            'user' => Lazy::whenLoaded('user', $tenant, fn () => UserData::from($tenant->user)),
        ];

        return $properties;
    }
}
