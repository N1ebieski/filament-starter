<?php

declare(strict_types=1);

namespace App\Queries\Permission\GetAvailableForTenant;

use App\Queries\Handler;
use Illuminate\Database\Eloquent\Collection;

final readonly class GetAvailableForTenantHandler extends Handler
{
    public function handle(GetAvailableForTenantQuery $query): Collection
    {
        /** @var Collection */
        $permissions = $query->permission->newQuery()
            ->where('name', 'like', 'tenant.%')
            ->get();

        return $permissions;
    }
}
