<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Data\Pipelines\ModelDataPipe\PrepareFromModelInterface;
use App\Data\Transformers\ValueObject\ValueObjectTransformer;
use App\Http\Resources\Resource;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\Tenant\TenantResource;
use App\Models\User\User;
use App\Overrides\Spatie\LaravelData\Lazy;
use App\ValueObjects\User\Name\Name;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Lazy as BaseLazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class UserResource extends Resource implements PrepareFromModelInterface
{
    public function __construct(
        public readonly int $id,
        #[WithTransformer(ValueObjectTransformer::class)]
        public readonly BaseLazy|Name $name,
        public readonly BaseLazy|DateTime|null $createdAt,
        public readonly BaseLazy|DateTime|null $updatedAt,
        #[DataCollectionOf(RoleResource::class)]
        public readonly BaseLazy|Collection $roles = new Collection,
        #[DataCollectionOf(TenantResource::class)]
        public readonly BaseLazy|Collection $tenants = new Collection,
    ) {}

    public static function prepareFromModel(User $user, array $properties): array
    {
        $properties = [
            ...$properties,
            'name' => Lazy::whenLoaded('name', $user, fn () => $user->name),
            'created_at' => Lazy::whenLoaded('created_at', $user, fn () => $user->createdAt),
            'updated_at' => Lazy::whenLoaded('updated_at', $user, fn () => $user->updatedAt),
            'roles' => Lazy::whenLoaded('roles', $user, fn () => RoleResource::collect($user->roles)),
            'tenants' => Lazy::whenLoaded('tenants', $user, fn () => TenantResource::collect($user->tenants)),
        ];

        return $properties;
    }
}
