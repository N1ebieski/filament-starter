<?php

declare(strict_types=1);

namespace App\Extends\Spatie\Permission\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
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

    private function getRolePivotTeamField(): string
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        return Config::get('permission.table_names.model_has_roles') . '.' . $permissionRegistrar->teamsKey;
    }

    private function getTeamField(): string
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        return Config::get('permission.table_names.roles') . '.' . $permissionRegistrar->teamsKey;
    }

    public function roles(): BelongsToMany
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        $relation = $this->morphToMany(
            Config::get('permission.models.role'),
            'authenticatable',
            Config::get('permission.table_names.model_has_roles'),
            Config::get('permission.column_names.model_morph_key'),
            $permissionRegistrar->pivotRole
        );

        if (!$permissionRegistrar->teams) {
            return $relation;
        }

        return $relation->where(function (Builder $query) use ($permissionRegistrar): Builder {
            return $query->where($this->getRolePivotTeamField(), $permissionRegistrar->getPermissionsTeamId())
                ->orWhereNull($this->getRolePivotTeamField());
        })
        ->where(function (Builder $query) use ($permissionRegistrar): Builder {
            return $query->whereNull($this->getTeamField())
                ->orWhere($this->getTeamField(), $permissionRegistrar->getPermissionsTeamId());
        });
    }
}
