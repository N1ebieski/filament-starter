<?php

declare(strict_types=1);

namespace App\Overrides\Spatie\Permission\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles as BaseHasRoles;

trait HasRoles
{
    use BaseHasRoles, HasPermissions {
        HasPermissions::permissions insteadof BaseHasRoles;
    }

    private function getRolePivotTeamField(): string
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        return Config::string('permission.table_names.model_has_roles').'.'.$permissionRegistrar->teamsKey;
    }

    private function getTeamField(): string
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        return Config::string('permission.table_names.roles').'.'.$permissionRegistrar->teamsKey;
    }

    public function roles(): BelongsToMany
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        $relation = $this->morphToMany(
            Config::string('permission.models.role'),
            'authenticatable',
            Config::string('permission.table_names.model_has_roles'),
            Config::string('permission.column_names.model_morph_key'),
            $permissionRegistrar->pivotRole
        );

        if (! $permissionRegistrar->teams) {
            return $relation;
        }

        return $relation
            ->where(fn (Builder $query): Builder => $query
                ->where($this->getRolePivotTeamField(), $permissionRegistrar->getPermissionsTeamId())
                ->orWhereNull($this->getRolePivotTeamField())
            )
            ->where(fn (Builder $query): Builder => $query
                ->whereNull($this->getTeamField())
                ->orWhere($this->getTeamField(), $permissionRegistrar->getPermissionsTeamId())
            );
    }

    public function tenantRoles(): BelongsToMany
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        $relation = $this->morphToMany(
            Config::string('permission.models.role'),
            'authenticatable',
            Config::string('permission.table_names.model_has_roles'),
            Config::string('permission.column_names.model_morph_key'),
            $permissionRegistrar->pivotRole
        );

        return $relation
            ->wherePivot($this->getRolePivotTeamField(), $permissionRegistrar->getPermissionsTeamId())
            ->where($this->getTeamField(), $permissionRegistrar->getPermissionsTeamId());
    }
}
