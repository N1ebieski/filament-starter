<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers\Get;

use App\Scopes\HasFilterableScopes;
use App\Queries\Shared\SearchBy\Drivers\Handler;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property-read Builder|HasFilterableScopes $builder
 */
final class GetHandler extends Handler
{
    public function handle(Get $get): Collection
    {
        return $this->builder->filterGet($get);
    }
}
