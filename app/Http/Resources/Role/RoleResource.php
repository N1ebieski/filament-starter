<?php

declare(strict_types=1);

namespace App\Http\Resources\Role;

use App\Data\Pipelines\ModelDataPipe\PrepareFromModelInterface;
use App\Http\Resources\Resource;
use App\Models\Role\Role;
use App\Overrides\Spatie\LaravelData\Lazy;
use DateTime;
use Spatie\LaravelData\Lazy as BaseLazy;

final class RoleResource extends Resource implements PrepareFromModelInterface
{
    public function __construct(
        public readonly int $id,
        public readonly BaseLazy|string $name,
        public readonly BaseLazy|DateTime|null $created_at,
        public readonly BaseLazy|DateTime|null $updated_at
    ) {}

    public static function prepareFromModel(Role $role, array $properties): array
    {
        $properties = [
            ...$properties,
            'name' => Lazy::whenLoaded('name', $role, fn () => $role->name->value),
            'created_at' => Lazy::whenLoaded('created_at', $role, fn () => $role->created_at),
            'updated_at' => Lazy::whenLoaded('updated_at', $role, fn () => $role->updated_at),
        ];

        return $properties;
    }
}
