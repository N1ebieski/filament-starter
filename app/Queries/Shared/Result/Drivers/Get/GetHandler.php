<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers\Get;

use App\Queries\Shared\Result\Drivers\Handler;
use App\Scopes\FiltersScopesInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read Builder&FiltersScopesInterface $builder
 */
final class GetHandler extends Handler
{
    public function __construct(private readonly Builder $builder) {}

    public function handle(Get $get): Collection
    {
        return $this->builder->filterGet($get);
    }
}
