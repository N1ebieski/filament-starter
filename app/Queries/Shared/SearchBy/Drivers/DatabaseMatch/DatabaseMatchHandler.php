<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\DatabaseMatch;

use App\Queries\Shared\SearchBy\Drivers\Handler;
use App\QueryBuilders\Shared\Search\SearchInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property-read Builder&SearchInterface $builder
 */
final class DatabaseMatchHandler extends Handler
{
    public function __construct(private readonly Builder $builder) {}

    public function handle(DatabaseMatch $databaseMatch): Builder
    {
        return $this->builder->filterSearchByDatabaseMatch($databaseMatch)
            ->filterSearchAttributesByDatabaseMatch($databaseMatch)
            ->when($databaseMatch->isOrderBy, function (Builder&SearchInterface $builder) use ($databaseMatch) {
                return $builder->filterOrderByDatabaseMatch($databaseMatch);
            });
    }
}
