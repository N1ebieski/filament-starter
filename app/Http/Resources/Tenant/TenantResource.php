<?php

declare(strict_types=1);

namespace App\Http\Resources\Tenant;

use DateTime;
use App\Models\Tenant\Tenant;
use App\Http\Resources\Resource;
use Spatie\LaravelData\Lazy as BaseLazy;
use App\Http\Resources\User\UserResource;
use App\Overrides\Spatie\LaravelData\Lazy;
use App\Data\Pipelines\ModelDataPipe\PrepareFromModelInterface;

final class TenantResource extends Resource implements PrepareFromModelInterface
{
    public function __construct(
        public readonly int $id,
        public readonly BaseLazy|string $name,
        public readonly BaseLazy|DateTime|null $created_at,
        public readonly BaseLazy|DateTime|null $updated_at,
        public readonly BaseLazy|UserResource $user
    ) {
    }

    public static function prepareFromModel(Tenant $tenant, array $properties): array
    {
        $properties = [
            ...$properties,
            'name' => Lazy::whenLoaded('name', $tenant, fn () => $tenant->name->value),
            'created_at' => Lazy::whenLoaded('created_at', $tenant, fn () => $tenant->created_at),
            'updated_at' => Lazy::whenLoaded('updated_at', $tenant, fn () => $tenant->updated_at),
            'user' => Lazy::whenLoaded('user', $tenant, fn () => UserResource::from($tenant->user)),
        ];

        return $properties;
    }
}
