<?php

declare(strict_types=1);

namespace App\Queries\Permission\GetAvailableForAdmin;

use App\Queries\Handler;
use App\ValueObjects\Role\Name\DefaultName;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final readonly class GetAvailableForAdminHandler extends Handler
{
    public function handle(GetAvailableForAdminQuery $query): Collection
    {
        /** @var Collection */
        $permissions = $query->permission->newQuery()
            ->when($query->role->exists, fn (Builder $builder) => $builder->when(
                $query->role->name->isEqualsDefault(DefaultName::User),
                fn (Builder $builder) => $builder->where('name', 'like', 'web.%')
                    ->orWhere('name', 'like', 'api.%')
            )->when(
                $query->role->name->isEqualsDefault(DefaultName::Api),
                fn (Builder $builder) => $builder->where('name', 'like', 'api.%')
            ))
            ->get();

        return $permissions;
    }
}
