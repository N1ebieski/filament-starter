<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use DateTime;
use App\Models\User\User;
use App\Http\Resources\Resource;
use Spatie\LaravelData\Lazy as BaseLazy;
use App\Http\Resources\Role\RoleResource;
use App\Overrides\Spatie\LaravelData\Lazy;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\Tenant\TenantResource;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use App\Data\Pipelines\ModelDataPipe\PrepareFromModelInterface;

final class UserResource extends Resource implements PrepareFromModelInterface
{
    public function __construct(
        public readonly int $id,
        public readonly BaseLazy|string $name,
        public readonly BaseLazy|DateTime|null $created_at,
        public readonly BaseLazy|DateTime|null $updated_at,
        #[DataCollectionOf(RoleResource::class)]
        public readonly BaseLazy|Collection $roles = new Collection(),
        #[DataCollectionOf(TenantResource::class)]
        public readonly BaseLazy|Collection $tenants = new Collection(),
    ) {
    }

    public static function prepareFromModel(User $user, array $properties): array
    {
        $properties = [
            ...$properties,
            'name' => Lazy::whenLoaded('name', $user, fn () => $user->name->value),
            'created_at' => Lazy::whenLoaded('created_at', $user, fn () => $user->created_at),
            'updated_at' => Lazy::whenLoaded('updated_at', $user, fn () => $user->updated_at),
            'roles' => Lazy::whenLoaded('roles', $user, fn () => RoleResource::collect($user->roles)),
            'tenants' => Lazy::whenLoaded('tenants', $user, fn () => TenantResource::collect($user->tenants)),
        ];

        return $properties;
    }
}
