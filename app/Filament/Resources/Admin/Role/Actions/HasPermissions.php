<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Actions;

use App\Models\Permission\Permission;
use App\Models\Role\Role;
use App\Queries\Permission\GetAvailableForAdmin\GetAvailableForAdminQuery;
use App\Queries\QueryBusInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

/**
 * @property-read QueryBusInterface $queryBus
 * @property-read Role $role
 */
trait HasPermissions
{
    public function getGroupedPermissions(?Role $role = null): SupportCollection
    {
        /** @var Collection */
        $permissions = $this->queryBus->execute(new GetAvailableForAdminQuery(
            role: $role ?? $this->role
        ));

        return $permissions->sortBy('name')
            // @phpstan-ignore-next-line@
            ->mapWithKeys(fn(Permission $permission): array => [$permission->id => $permission->name->value])
            ->groupBy(function (string $item): string {
                preg_match('/[^.]*?\.([a-z]+){1}\..*/', $item, $group);

                return $group[1] ?? '';
            }, preserveKeys: true);
    }
}
