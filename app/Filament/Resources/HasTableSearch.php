<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatchFactory;
use App\Scopes\SearchScopesInterface;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Override;

/**
 * @mixin InteractsWithTable
 */
trait HasTableSearch
{
    /**
     * @param  Builder&SearchScopesInterface  $query
     */
    #[Override]
    protected function applyGlobalSearchToTableQuery(Builder $query): Builder
    {
        $search = $this->getTableSearch();

        if ($search && mb_strlen($search) > 2) {
            /** @var Model */
            $model = new ($this->getTable()->getModel());

            return $query->filterSearchBy(DatabaseMatchFactory::makeDatabaseMatch(
                term: $search,
                isOrderBy: is_null($this->getTableSortColumn()),
                model: $model
            ));
        }

        return $query;
    }
}
