<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions;

use App\Queries\QueryBusInterface;
use App\Models\Permission\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use App\Queries\Permission\GetAvailableForTenant\GetAvailableForTenantQuery;

/**
 * @property-read QueryBusInterface $queryBus
 */
trait HasPermissions
{
    public function getGroupedPermissions(): SupportCollection
    {
        /** @var Collection */
        $permissions = $this->queryBus->execute(new GetAvailableForTenantQuery());

        return $permissions->sortBy('name')
            ->mapWithKeys(function (Permission $permission): array {
                return [$permission->id => $permission->name->value];
            })
            ->groupBy(function (string $item): string {
                preg_match('/[^.]*?\.([a-z]+){1}\..*/', $item, $group);

                return $group[1] ?? '';
            }, preserveKeys: true);
    }
}
