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
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Lazy as BaseLazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * @property-read int $id
 * @property-read Name $name
 * @property-read Email $email
 * @property-read StatusEmail $statusEmail
 * @property-read DateTime|null $createdAt
 * @property-read DateTime|null $updatedAt
 * @property-read DateTime|null $emailVerifiedAt
 * @property-read Collection<RoleData> $roles
 * @property-read Collection<RoleData> $tenantRoles
 * @property-read Collection<TenantData> $tenants
 * @property-read Collection<TenantData> $ownedTenants
 */
#[MapName(SnakeCaseMapper::class)]
final class UserData extends Data implements PrepareFromModelInterface
{
    public function __construct(
        public readonly int $id,
        public readonly BaseLazy|Name $name,
        public readonly BaseLazy|Email $email,
        public readonly BaseLazy|StatusEmail $statusEmail,
        public readonly BaseLazy|DateTime|null $createdAt,
        public readonly BaseLazy|DateTime|null $updatedAt,
        public readonly BaseLazy|DateTime|null $emailVerifiedAt,
        public readonly BaseLazy|Collection $roles,
        public readonly BaseLazy|Collection $tenantRoles,
        public readonly BaseLazy|Collection $tenants,
        public readonly BaseLazy|Collection $ownedTenants
    ) {}

    public static function prepareFromModel(User $user, array $properties): array
    {
        $properties = [
            ...$properties,
            'name' => Lazy::whenLoaded('name', $user, fn () => $user->name),
            'email' => Lazy::whenLoaded('email', $user, fn () => $user->email),
            'status_email' => Lazy::whenLoaded('email_verified_at', $user, fn () => $user->statusEmail),
            'created_at' => Lazy::whenLoaded('created_at', $user, fn () => $user->createdAt),
            'updated_at' => Lazy::whenLoaded('updated_at', $user, fn () => $user->updatedAt),
            'email_verified_at' => Lazy::whenLoaded('email_verified_at', $user, fn () => $user->emailVerifiedAt),
            'roles' => Lazy::whenLoaded('roles', $user, fn () => RoleData::collect($user->roles)),
            'tenant_roles' => Lazy::whenLoaded('tenantsRoles', $user, fn () => RoleData::collect($user->tenantRoles)),
            'tenants' => Lazy::whenLoaded('tenants', $user, fn () => TenantData::collect($user->tenants)),
            'owned_tenants' => Lazy::whenLoaded('ownedTenants', $user, fn () => TenantData::collect($user->ownedTenants)),
        ];

        return $properties;
    }
}
