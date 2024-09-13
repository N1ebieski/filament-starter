<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\DatabaseMatch;

use App\Scopes\SearchScopesInterface;
use App\Queries\Shared\SearchBy\Drivers\Handler;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property-read Builder&SearchScopesInterface $builder
 */
final class DatabaseMatchHandler extends Handler
{
    public function __construct(private readonly Builder $builder)
    {
    }

    public function handle(DatabaseMatch $databaseMatch): Builder
    {
        return $this->builder->filterSearchByDatabaseMatch($databaseMatch)
            ->filterSearchAttributesByDatabaseMatch($databaseMatch)
            ->when($databaseMatch->isOrderBy, function (Builder|SearchScopesInterface $builder) use ($databaseMatch) {
                return $builder->filterOrderByDatabaseMatch($databaseMatch);
            });
    }
}
