<?php

declare(strict_types=1);

namespace App\QueryBuilders\User;

use App\Models\Role\Role;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\QueryBuilders\Shared\Filters\HasFilters;
use App\QueryBuilders\Shared\Search\SearchInterface;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * @template TModel of \App\Models\User\User
 */
final class UserQueryBuilder extends Builder implements SearchInterface
{
    use HasFilters;

    public function filterStatusEmail(?StatusEmail $status): self
    {
        return $this->unless(is_null($status), function (Builder $builder) use ($status): Builder {
            /** @var StatusEmail $status */
            return $builder->when(
                $status->isEquals(StatusEmail::Verified),
                fn (Builder $builder): Builder => $builder->whereNotNull('email_verified_at'),
                fn (Builder $builder): Builder => $builder->whereNull('email_verified_at')
            );
        });
    }

    public function filterRoles(?Collection $roles): self
    {
        return $this->when($roles?->isNotEmpty(), function (Builder $builder) use ($roles): Builder {
            /** @var Collection $roles */
            return $builder->whereHas('roles', function (Builder $builder) use ($roles): Builder {
                /** @var User $user */
                $user = $this->getModel();

                /** @var Role $role */
                $role = $user->roles()->make();

                return $builder->whereIn("{$role->getTable()}.id", $roles->pluck('id'));
            });
        });
    }

    public function filterTenants(?Collection $tenants): self
    {
        return $this->when($tenants?->isNotEmpty(), function (Builder $builder) use ($tenants): Builder {
            /** @var Collection $tenants */
            return $builder->whereHas('tenants', function (Builder $builder) use ($tenants): Builder {
                /** @var User $user */
                $user = $this->getModel();

                /** @var Tenant $tenant */
                $tenant = $user->tenants()->make();

                return $builder->whereIn("{$tenant->getTable()}.id", $tenants->pluck('id'));
            });
        });
    }

    public function withAll(): self
    {
        return $this->with(['roles']);
    }
}
