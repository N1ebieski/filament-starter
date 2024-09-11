<?php

declare(strict_types=1);

namespace App\Scopes\Role;

use App\Models\Role\Role;
use App\Scopes\HasFiltersScopes;

/**
 * @mixin Role
 */
trait HasRoleScopes
{
    use HasFiltersScopes;
}
