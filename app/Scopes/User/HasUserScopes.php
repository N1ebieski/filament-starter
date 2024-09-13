<?php

declare(strict_types=1);

namespace App\Scopes\User;

use App\Models\Role\Role;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Scopes\HasFiltersScopes;
use Illuminate\Database\Eloquent\Collection;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @mixin User
 */
trait HasUserScopes
{
    use HasFiltersScopes;

    public function scopeFilterStatusEmail(Builder $builder, ?StatusEmail $status): Builder
    {
        return $builder->when(!is_null($status), function (Builder $builder) use ($status): Builder {
            /** @var StatusEmail $status */

            return $builder->when($status->isEquals(StatusEmail::Verified), function (Builder $builder): Builder {
                return $builder->whereNotNull('email_verified_at');
            }, function (Builder $builder): Builder {
                return $builder->whereNull('email_verified_at');
            });
        });
    }

    public function scopeFilterRoles(Builder $builder, Collection $roles): Builder
    {
        return $builder->when($roles->isNotEmpty(), function (Builder $builder) use ($roles): Builder {
            return $builder->whereHas('roles', function (Builder $builder) use ($roles): Builder {
                /** @var Role */
                $role = $this->roles()->make();

                return $builder->whereIn("{$role->getTable()}.id", $roles->pluck('id'));
            });
        });
    }

    public function scopeFilterTenants(Builder $builder, Collection $tenants): Builder
    {
        return $builder->when($tenants->isNotEmpty(), function (Builder $builder) use ($tenants): Builder {
            return $builder->whereHas('tenants', function (Builder $builder) use ($tenants): Builder {
                /** @var Tenant */
                $tenant = $this->tenants()->make();

                return $builder->whereIn("{$tenant->getTable()}.id", $tenants->pluck('id'));
            });
        });
    }

    public function scopeWithAll(Builder $builder): Builder
    {
        return $builder->with(['roles']);
    }
}
