<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Override;
use App\Scopes\HasSearchScopes;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatchFactory;

/**
 * @mixin InteractsWithTable
 */
trait HasTableSearch
{
    /**
     * @param Builder|HasSearchScopes $query
     */
    #[Override]
    protected function applyGlobalSearchToTableQuery(Builder $query): Builder
    {
        $search = $this->getTableSearch();

        if ($search && mb_strlen($search) > 2) {
            return $query->filterSearchBy(DatabaseMatchFactory::makeDatabaseMatch(
                term: $search,
                isOrderBy: is_null($this->getTableSortColumn()),
                model: new ($this->getTable()->getModel())
            ));
        }

        return $query;
    }
}
