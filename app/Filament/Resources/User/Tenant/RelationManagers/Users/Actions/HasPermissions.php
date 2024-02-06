<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions;

use App\Queries\QueryBus;
use App\Models\Permission\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use App\Queries\Permission\GetAvailableForTenant\GetAvailableForTenantQuery;

/**
 * @property-read QueryBus $queryBus
 */
trait HasPermissions
{
    public function getGroupedPermissions(): SupportCollection
    {
        /** @var Collection */
        $permissions = $this->queryBus->execute(new GetAvailableForTenantQuery());

        return $permissions->sortBy('name')
            ->mapWithKeys(function (Permission $permission) {
                return [$permission->id => $permission->name];
            })
            ->groupBy(function (string $item) {
                preg_match('/[^.]*?\.([a-z]+){1}\..*/', $item, $group);

                return $group[1] ?? null;
            }, preserveKeys: true);
    }
}
