<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;
use Spatie\LaravelData\DataCollection;
use Illuminate\Database\Eloquent\Model;
use App\Support\Resource\ResourceHelper;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Contracts\Pagination\CursorPaginator as CursorPaginatorContract;

final class ResourceFactory
{
    public static function makeResource(Model $model): Resource
    {
        /** @var Resource */
        $resourceName = ResourceHelper::getResourceName($model);

        return $resourceName::from($model);
    }

    public static function makeCollection(
        Paginator|Collection $collection,
        ?string $into = null
    ): array|DataCollection|PaginatedDataCollection|CursorPaginatedDataCollection|Enumerable|AbstractPaginator|PaginatorContract|AbstractCursorPaginator|CursorPaginatorContract|LazyCollection|Collection {
        if ($collection->isNotEmpty()) {
            /** @var Model */
            //@phpstan-ignore-next-line
            $model = $collection[0];

            /** @var Resource */
            $resourceName = ResourceHelper::getResourceName($model);

            return $resourceName::collect($collection, $into);
        }

        return $collection;
    }
}
