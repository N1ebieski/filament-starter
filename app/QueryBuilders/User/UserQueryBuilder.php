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
        return $this->unless(is_null($status), fn (Builder $builder): Builder =>
            /** @var StatusEmail $status */
            $builder->when(
                $status->isEquals(StatusEmail::Verified),
                fn (Builder $builder): Builder => $builder->whereNotNull('email_verified_at'),
                fn (Builder $builder): Builder => $builder->whereNull('email_verified_at')
            )
        );
    }

    public function filterRoles(?Collection $roles): self
    {
        return $this->when($roles?->isNotEmpty(), fn (Builder $builder): Builder =>
            /** @var Collection $roles */
            $builder->whereHas('roles', function (Builder $builder) use ($roles): Builder {
                /** @var User */
                $user = $this->getModel();

                /** @var Role */
                $role = $user->roles()->make();

                return $builder->whereIn("{$role->getTable()}.id", $roles->pluck('id'));
            }));
    }

    public function filterTenants(?Collection $tenants): self
    {
        return $this->when($tenants?->isNotEmpty(), fn (Builder $builder): Builder =>
            /** @var Collection $tenants */
            $builder->whereHas('tenants', function (Builder $builder) use ($tenants): Builder {
                /** @var User */
                $user = $this->getModel();

                /** @var Tenant */
                $tenant = $user->tenants()->make();

                return $builder->whereIn("{$tenant->getTable()}.id", $tenants->pluck('id'));
            }));
    }

    /**
     * @return UserQueryBuilder
     */
    public function withAll()
    {
        return $this->with(['roles']);
    }
}
