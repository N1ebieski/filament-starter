<?php

declare(strict_types=1);

namespace App\Queries\Permission\GetAvailableForAdmin;

use App\Queries\Handler;
use App\ValueObjects\Role\Name\DefaultName;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;

final class GetAvailableForAdminHandler extends Handler
{
    public function handle(GetAvailableForAdminQuery $query): Collection
    {
        /** @var Collection */
        $permissions = $query->permission->newQuery()
            ->when($query->role->exists, function (Builder $builder) use ($query) {
                return $builder->when(
                    $query->role->name->isEqualsDefault(DefaultName::User),
                    function (Builder $builder) {
                        return $builder->where('name', 'like', 'web.%')
                            ->orWhere('name', 'like', 'api.%');
                    }
                )->when(
                    $query->role->name->isEqualsDefault(DefaultName::Api),
                    function (Builder $builder) {
                        return $builder->where('name', 'like', 'api.%');
                    }
                );
            })
            ->get();

        return $permissions;
    }
}
