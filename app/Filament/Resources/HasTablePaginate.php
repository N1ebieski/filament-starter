<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Queries\Shared\Result\Drivers\Paginate\Paginate;
use App\Scopes\FiltersScopesInterface;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Override;

/**
 * @mixin InteractsWithTable
 */
trait HasTablePaginate
{
    /**
     * @param  Builder&FiltersScopesInterface  $query
     */
    #[Override]
    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->filterPaginate(new Paginate(
            perPage: (int) $this->getTableRecordsPerPage(),
            page: $this->getPage()
        ));
    }
}
