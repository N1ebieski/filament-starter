<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Override;
use App\Scopes\HasFilterableScopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Queries\Shared\Result\Drivers\Paginate\Paginate;

/**
 * @mixin InteractsWithTable
 */
trait HasTablePaginate
{
    /**
     * @param Builder|HasFilterableScopes $query
     */
    #[Override]
    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->filterPaginate(new Paginate(
            perPage: (int)$this->getTableRecordsPerPage(),
            page: $this->getPage()
        ));
    }
}
