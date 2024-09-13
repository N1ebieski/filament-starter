<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers\Get;

use App\Scopes\FiltersScopesInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Queries\Shared\Result\Drivers\Handler;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property-read Builder&FiltersScopesInterface $builder
 */
final class GetHandler extends Handler
{
    public function __construct(private readonly Builder $builder)
    {
    }

    public function handle(Get $get): Collection
    {
        return $this->builder->filterGet($get);
    }
}
