<?php

declare(strict_types=1);

namespace App\Http\Resources\Tenant;

use App\Data\Pipelines\ModelDataPipe\PrepareFromModelInterface;
use App\Data\Transformers\ValueObject\ValueObjectTransformer;
use App\Http\Resources\Resource;
use App\Http\Resources\User\UserResource;
use App\Models\Tenant\Tenant;
use App\Overrides\Spatie\LaravelData\Lazy;
use App\ValueObjects\Tenant\Name\Name;
use DateTime;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Lazy as BaseLazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class TenantResource extends Resource implements PrepareFromModelInterface
{
    public function __construct(
        public readonly int $id,
        #[WithTransformer(ValueObjectTransformer::class)]
        public readonly BaseLazy|Name $name,
        public readonly BaseLazy|DateTime|null $createdAt,
        public readonly BaseLazy|DateTime|null $updatedAt,
        public readonly BaseLazy|UserResource $user
    ) {}

    public static function prepareFromModel(Tenant $tenant, array $properties): array
    {
        $properties = [
            ...$properties,
            'name' => Lazy::whenLoaded('name', $tenant, fn () => $tenant->name),
            'created_at' => Lazy::whenLoaded('created_at', $tenant, fn () => $tenant->createdAt),
            'updated_at' => Lazy::whenLoaded('updated_at', $tenant, fn () => $tenant->updatedAt),
            'user' => Lazy::whenLoaded('user', $tenant, fn () => UserResource::from($tenant->user)),
        ];

        return $properties;
    }
}
