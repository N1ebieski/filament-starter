<?php

declare(strict_types=1);

namespace App\Extends\Spatie\Permission\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasPermissions as BaseHasPermissions;

trait HasPermissions
{
    use BaseHasPermissions;

    private function getPermissionPivotTeamField(): string
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        return Config::get('permission.table_names.model_has_permissions') . '.' . $permissionRegistrar->teamsKey;
    }

    public function permissions(): BelongsToMany
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        $relation = $this->morphToMany(
            Config::get('permission.models.permission'),
            'authenticatable',
            Config::get('permission.table_names.model_has_permissions'),
            Config::get('permission.column_names.model_morph_key'),
            $permissionRegistrar->pivotPermission
        );

        if (!$permissionRegistrar->teams) {
            return $relation;
        }

        return $relation->where($this->getPermissionPivotTeamField(), $permissionRegistrar->getPermissionsTeamId())
            ->orWhereNull($this->getPermissionPivotTeamField());
    }

    public function tenantPermissions(): BelongsToMany
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        $relation = $this->morphToMany(
            Config::get('permission.models.permission'),
            'authenticatable',
            Config::get('permission.table_names.model_has_permissions'),
            Config::get('permission.column_names.model_morph_key'),
            $permissionRegistrar->pivotPermission
        );

        return $relation->where($this->getPermissionPivotTeamField(), $permissionRegistrar->getPermissionsTeamId());
    }
}
