<?php

declare(strict_types=1);

namespace App\Queries\SearchBy;

use App\Data\Data\Data;
use App\Scopes\Search\Driver;

final class SearchBy extends Data
{
    public function __construct(
        public readonly string $term,
        public readonly Driver $driver = Driver::DatabaseMatch
    ) {
    }
}
