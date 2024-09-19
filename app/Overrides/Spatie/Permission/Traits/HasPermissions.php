<?php

declare(strict_types=1);

namespace App\Overrides\Spatie\Permission\Traits;

use App\Models\Permission\Permission;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasPermissions as BaseHasPermissions;

trait HasPermissions
{
    use BaseHasPermissions;

    private function getPermissionPivotTeamField(): string
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        return Config::get('permission.table_names.model_has_permissions').'.'.$permissionRegistrar->teamsKey;
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

        if (! $permissionRegistrar->teams) {
            return $relation;
        }

        return $relation->wherePivot($this->getPermissionPivotTeamField(), $permissionRegistrar->getPermissionsTeamId())
            ->orWherePivotNull($this->getPermissionPivotTeamField());
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

        return $relation->wherePivot($this->getPermissionPivotTeamField(), $permissionRegistrar->getPermissionsTeamId());
    }

    /**
     * Revoke the given permission(s).
     *
     * @param  Permission|Permission[]|string|string[]|\BackedEnum  $permission
     * @return $this
     */
    public function revokeTenantPermissionTo($permission)
    {
        $this->tenantPermissions()->detach($this->getStoredPermission($permission));

        if (is_a($this, Role::class)) {
            $this->forgetCachedPermissions();
        }

        $this->forgetWildcardPermissionIndex();

        $this->unsetRelation('permissions');

        return $this;
    }
}
