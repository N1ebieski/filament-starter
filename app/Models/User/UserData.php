<?php

declare(strict_types=1);

namespace App\Models\User;

use App\Data\Data\Data;
use App\Data\Pipelines\ModelDataPipe\PrepareFromModelInterface;
use App\Models\Role\RoleData;
use App\Models\Tenant\TenantData;
use App\Overrides\Spatie\LaravelData\Lazy;
use App\ValueObjects\User\Email\Email;
use App\ValueObjects\User\Name\Name;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use DateTime;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Lazy as BaseLazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * @property-read Collection<RoleData> $roles
 * @property-read Collection<RoleData> $tenantRoles
 * @property-read Collection<TenantData> $tenants
 * @property-read Collection<TenantData> $ownedTenants
 */
#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class UserData extends Data implements PrepareFromModelInterface
{
    public function __construct(
        public readonly int $id,
        public readonly Name $name,
        public readonly Email $email,
        public readonly StatusEmail $statusEmail,
        public readonly ?DateTime $createdAt,
        public readonly ?DateTime $updatedAt,
        public readonly ?DateTime $emailVerifiedAt,
        public readonly BaseLazy|Collection $roles,
        public readonly BaseLazy|Collection $tenantRoles,
        public readonly BaseLazy|Collection $tenants,
        public readonly BaseLazy|Collection $ownedTenants
    ) {}

    public static function prepareFromModel(User $user, array $properties): array
    {
        $properties = [
            ...$properties,
            'roles' => Lazy::whenLoaded('roles', $user, fn () => RoleData::collect($user->roles)),
            'tenantRoles' => Lazy::whenLoaded('tenantsRoles', $user, fn () => RoleData::collect($user->tenantRoles)),
            'tenants' => Lazy::whenLoaded('tenants', $user, fn () => TenantData::collect($user->tenants)),
            'ownedTenants' => Lazy::whenLoaded('ownedTenants', $user, fn () => TenantData::collect($user->ownedTenants)),
        ];

        return $properties;
    }
}
