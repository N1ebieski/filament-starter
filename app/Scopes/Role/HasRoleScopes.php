<?php

declare(strict_types=1);

namespace App\Scopes\Role;

use App\Models\Role\Role;
use App\Scopes\HasFilterableScopes;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @mixin Role
 */
trait HasRoleScopes
{
    use HasFilterableScopes;

    public function scopeWithAllRelations(Builder $builder): Builder
    {
        return $builder;
    }
}
