<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers;

use App\Scopes\SearchScopesInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property-read Builder&SearchScopesInterface $builder
 */
abstract class Handler
{
    public function __construct(protected readonly Builder $builder)
    {
    }
}
