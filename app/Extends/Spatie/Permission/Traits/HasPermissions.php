<?php

declare(strict_types=1);

namespace App\Extends\Spatie\Permission\Traits;

use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasPermissions as BaseHasPermissions;

trait HasPermissions
{
    use BaseHasPermissions;

    public function permissions(): BelongsToMany
    {
        $relation = $this->morphToMany(
            config('permission.models.permission'),
            'authenticatable',
            config('permission.table_names.model_has_permissions'),
            config('permission.column_names.model_morph_key'),
            app(PermissionRegistrar::class)->pivotPermission
        );

        if (! app(PermissionRegistrar::class)->teams) {
            return $relation;
        }

        $pivotTeamField = config('permission.table_names.model_has_permissions') . '.' . app(PermissionRegistrar::class)->teamsKey;

        return $relation->where($pivotTeamField, getPermissionsTeamId())->orWhereNull($pivotTeamField);
    }
}
