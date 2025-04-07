<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Queries\Shared\SearchBy\Drivers\Scout\Scout;
use App\QueryBuilders\Shared\Search\SearchInterface;
use Filament\Tables\Concerns\CanSortRecords;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Override;

/**
 * @mixin InteractsWithTable
 */
trait HasTableSearch
{
    use CanSortRecords {
        CanSortRecords::applySortingToTableQuery as baseApplySortingToTableQuery;
    }

    /**
     * @param  Builder&SearchInterface  $query
     */
    #[Override]
    protected function applyGlobalSearchToTableQuery(Builder $query): Builder
    {
        $search = $this->getTableSearch();

        if (is_string($search) && mb_strlen($search) > 0) {
            $sort = $this->getTableSortColumn();

            return $query->filterSearchBy(new Scout(
                query: $search,
                isOrderBy: is_null($sort),
            ));
        }

        return $query;
    }

    /**
     * @param  Builder&SearchInterface  $query
     */
    #[Override]
    protected function applySortingToTableQuery(Builder $query): Builder
    {
        $search = $this->getTableSearch();
        $sort = $this->getTableSortColumn();

        if (is_string($search) && mb_strlen($search) > 0 && is_null($sort)) {
            return $query;
        }

        return $this->baseApplySortingToTableQuery($query);
    }
}
