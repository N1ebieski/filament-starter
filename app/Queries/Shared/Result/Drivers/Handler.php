<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers;

use App\Scopes\FilterableScopesInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property-read Builder&FilterableScopesInterface $builder
 */
abstract class Handler
{
    public function __construct(protected readonly Builder $builder)
    {
    }
}
