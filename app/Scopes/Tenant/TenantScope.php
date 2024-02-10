<?php

declare(strict_types=1);

namespace App\Scopes\Tenant;

use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class TenantScope implements Scope
{
    public function __construct(private readonly Tenant $tenant)
    {
    }

    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereBelongsTo($this->tenant);
    }
}
