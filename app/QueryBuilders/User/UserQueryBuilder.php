<?php

declare(strict_types=1);

namespace App\QueryBuilders\User;

use App\Models\Role\Role;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\QueryBuilders\Shared\Filters\HasFilters;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * @template TModel of \App\Models\User\User
 */
final class UserQueryBuilder extends Builder
{
    use HasFilters;

    public function filterStatusEmail(?StatusEmail $status): self
    {
        return $this->when(! is_null($status), function (Builder $builder) use ($status): Builder {
            /** @var StatusEmail $status */
            return $builder->when($status->isEquals(StatusEmail::Verified), function (Builder $builder): Builder {
                return $builder->whereNotNull('email_verified_at');
            }, function (Builder $builder): Builder {
                return $builder->whereNull('email_verified_at');
            });
        });
    }

    public function filterRoles(?Collection $roles): self
    {
        return $this->when($roles?->isNotEmpty(), function (Builder $builder) use ($roles): Builder {
            /** @var Collection $roles */
            return $builder->whereHas('roles', function (Builder $builder) use ($roles): Builder {
                /** @var User */
                $user = $this->getModel();

                /** @var Role */
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
                /** @var User */
                $user = $this->getModel();

                /** @var Tenant */
                $tenant = $user->tenants()->make();

                return $builder->whereIn("{$tenant->getTable()}.id", $tenants->pluck('id'));
            });
        });
    }

    /**
     * @return UserQueryBuilder
     */
    public function withAll()
    {
        return $this->with(['roles']);
    }
}
