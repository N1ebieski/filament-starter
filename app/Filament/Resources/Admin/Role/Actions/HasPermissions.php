<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Actions;

use App\Models\Role\Role;
use App\Queries\QueryBus;
use App\Models\Permission\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use App\Queries\Permission\GetAvailable\GetAvailableQuery;

/**
 * @property-read QueryBus $queryBus
 * @property-read Role $role
 */
trait HasPermissions
{
    public function getGroupedPermissions(?Role $role = null): SupportCollection
    {
        /** @var Collection */
        $permissions = $this->queryBus->execute(new GetAvailableQuery(
            role: $role ?? $this->role
        ));

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
