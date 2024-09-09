<?php

declare(strict_types=1);

namespace App\Queries\Result;

use App\Scopes\HasFilterableScopes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;

final class Get implements ResultInterface
{
    public function __construct(public readonly ?int $take = null)
    {
    }

    /**
     * @param Builder|HasFilterableScopes $builder
     */
    public function getResultBuilder(Builder $builder): Collection
    {
        return $builder->filterGet($this);
    }
}
