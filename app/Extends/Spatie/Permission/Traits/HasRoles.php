<?php

declare(strict_types=1);

namespace App\Extends\Spatie\Permission\Traits;

use Spatie\Permission\PermissionRegistrar;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles as BaseHasRoles;
use App\Extends\Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    use BaseHasRoles, HasPermissions {
        HasPermissions::permissions insteadof BaseHasRoles;
    }

    public function roles(): BelongsToMany
    {
        $relation = $this->morphToMany(
            config('permission.models.role'),
            'authenticatable',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            app(PermissionRegistrar::class)->pivotRole
        );

        if (! app(PermissionRegistrar::class)->teams) {
            return $relation;
        }

        $teamField = config('permission.table_names.roles') . '.' . app(PermissionRegistrar::class)->teamsKey;

        return $relation->where(function (Builder $query) {
            $pivotTeamField = config('permission.table_names.model_has_roles') . '.' . app(PermissionRegistrar::class)->teamsKey;

            return $query->where($pivotTeamField, getPermissionsTeamId())->orWhereNull($pivotTeamField);
        })
        ->where(fn ($q) => $q->whereNull($teamField)->orWhere($teamField, getPermissionsTeamId()));
    }
}
