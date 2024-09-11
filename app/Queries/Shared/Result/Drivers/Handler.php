<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers;

use App\Scopes\FilterableScopesInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property-read Builder&FilterableScopesInterface $builder
 * @method Builder handle(\App\Queries\Shared\Result\ResultInterface $result)
 */
abstract class Handler
{
    public function __construct(protected readonly Builder $builder)
    {
    }
}
