<?php

declare(strict_types=1);

namespace App\Http\Resources\Role;

use App\Data\Pipelines\ModelDataPipe\PrepareFromModelInterface;
use App\Data\Transformers\ValueObject\ValueObjectTransformer;
use App\Http\Resources\Resource;
use App\Models\Role\Role;
use App\Overrides\Spatie\LaravelData\Lazy;
use App\ValueObjects\Role\Name\Name;
use DateTime;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Lazy as BaseLazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class RoleResource extends Resource implements PrepareFromModelInterface
{
    public function __construct(
        public readonly int $id,
        #[WithTransformer(ValueObjectTransformer::class)]
        public readonly BaseLazy|Name $name,
        public readonly BaseLazy|DateTime|null $createdAt,
        public readonly BaseLazy|DateTime|null $updatedAt
    ) {}

    public static function prepareFromModel(Role $role, array $properties): array
    {
        $properties = [
            ...$properties,
            'name' => Lazy::whenLoaded('name', $role, fn () => $role->name),
            'createdAt' => Lazy::whenLoaded('createdAt', $role, fn () => $role->createdAt),
            'updatedAt' => Lazy::whenLoaded('updatedAt', $role, fn () => $role->updatedAt),
        ];

        return $properties;
    }
}
