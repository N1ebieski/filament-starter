<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Support\Resource\ResourceHelper;
use Illuminate\Contracts\Pagination\CursorPaginator as CursorPaginatorContract;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

final class ResourceFactory
{
    public static function makeResource(Model $model): Resource
    {
        /** @var \App\Http\Resources\Resource */
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

            /** @var \App\Http\Resources\Resource */
            $resourceName = ResourceHelper::getResourceName($model);

            return $resourceName::collect($collection, $into);
        }

        return $collection;
    }
}
