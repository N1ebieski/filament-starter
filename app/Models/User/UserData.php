<?php

declare(strict_types=1);

namespace App\Models\User;

use App\Data\Pipelines\ModelDataPipe\PrepareFromModelInterface;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Models\ModelData;
use App\Models\Role\RoleData;
use App\Models\Tenant\TenantData;
use App\ValueObjects\User\Email\Email;
use App\ValueObjects\User\Name\Name;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use DateTime;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

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
final class UserData extends ModelData implements ObjectDefaultsInterface, PrepareFromModelInterface
{
    public function __construct(
        public readonly int $id,
        public readonly Lazy|StatusEmail $statusEmail,
        public readonly Optional|Name $name = new Optional,
        public readonly Optional|Email $email = new Optional,
        public readonly Optional|DateTime|null $createdAt = new Optional,
        public readonly Optional|DateTime|null $updatedAt = new Optional,
        public readonly Optional|DateTime|null $emailVerifiedAt = new Optional,
        #[DataCollectionOf(RoleData::class)]
        public readonly Optional|Collection $roles = new Optional,
        #[DataCollectionOf(RoleData::class)]
        public readonly Optional|Collection $tenantRoles = new Optional,
        #[DataCollectionOf(TenantData::class)]
        public readonly Optional|Collection $tenants = new Optional,
        #[DataCollectionOf(TenantData::class)]
        public readonly Optional|Collection $ownedTenants = new Optional,
    ) {}

    public static function prepareFromModel(User $user, array $properties): array
    {
        $properties = [
            ...$properties,
            'status_email' => Lazy::when(fn () => $user->isAttributeLoaded('email_verified_at'), fn () => $user->statusEmail),
        ];

        return $properties;
    }
}
