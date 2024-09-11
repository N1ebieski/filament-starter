<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers;

use App\Scopes\FiltersScopesInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property-read Builder&FiltersScopesInterface $builder
 * @method Builder handle(\App\Queries\Shared\Result\ResultInterface $result)
 */
abstract class Handler
{
    public function __construct(protected readonly Builder $builder)
    {
    }
}
