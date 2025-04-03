<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions;

use App\Models\Permission\Permission;
use App\Queries\Permission\GetAvailableForTenant\GetAvailableForTenantQuery;
use App\Queries\QueryBusInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

/**
 * @property-read QueryBusInterface $queryBus
 */
trait HasPermissions
{
    public function getGroupedPermissions(): SupportCollection
    {
        /** @var Collection */
        $permissions = $this->queryBus->execute(new GetAvailableForTenantQuery);

        return $permissions->sortBy('name')
            // @phpstan-ignore-next-line@
            ->mapWithKeys(fn (Permission $permission): array => [$permission->id => $permission->name->value])
            ->groupBy(function (string $item): string {
                preg_match('/[^.]*?\.([a-z]+){1}\..*/', $item, $group);

                return $group[1] ?? '';
            }, preserveKeys: true);
    }
}
