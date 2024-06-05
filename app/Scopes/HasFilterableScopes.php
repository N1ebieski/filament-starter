<?php

declare(strict_types=1);

namespace App\Scopes;

use App\Queries\Get;
use App\Queries\Search;
use App\Queries\OrderBy;
use App\Queries\Paginate;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use App\Support\Query\Columns\ColumnsHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @property array<string> $searchableAttributes
 */
trait HasFilterableScopes
{
    public function scopeFilterSearchAttributes(Builder $builder, ?Search $search = null): Builder
    {
        return $builder->when(!is_null($search), function (Builder $builder) use ($search) {
            /** @var Search $search */
            return $builder->when(!is_null($search->attributes), function (Builder $builder) use ($search) {
                /** @var array */
                $attributes = $search->attributes;

                return $builder->where(function (Builder $builder) use ($attributes) {
                    foreach ($this->searchableAttributes as $attr) {
                        $builder = $builder->when(array_key_exists($attr, $attributes), function (Builder $builder) use ($attr, $attributes) {
                            return $builder->where("{$this->getTable()}.{$attr}", $attributes[$attr]);
                        });
                    }

                    return $builder;
                });
            });
        });
    }

    public function scopeFilterSearch(Builder $builder, ?Search $search = null, string $boolean = 'and'): Builder
    {
        return $builder->when(!is_null($search), function (Builder $builder) use ($search, $boolean) {
            /** @var Search $search */
            return $builder->when(!is_null($search->getSearchAsString()), function (Builder $builder) use ($search, $boolean) {
                /** @var ColumnsHelper */
                $columnsHelper = App::make(ColumnsHelper::class);

                $builder = $builder->whereRaw(
                    "MATCH ({$columnsHelper->getColumnsAsString($this->searchable)}) AGAINST (? IN BOOLEAN MODE)",
                    [$search->getSearchAsString()],
                    $boolean
                );

                foreach ($this->searchable as $column) {
                    $builder = $builder->selectRaw(
                        "MATCH ({$columnsHelper->getColumnWithTicks($column)}) AGAINST (? IN BOOLEAN MODE) AS {$columnsHelper->getColumnWithSnakes($column . '_relevance')}",
                        [$search->getSearchAsString()]
                    );
                }

                return $builder;
            });
        });
    }

    public function scopeFilterOrderBySearch(Builder $builder, ?Search $search = null): Builder
    {
        return $builder->when(!is_null($search), function (Builder $builder) use ($search) {
            /** @var Search $search */
            return $builder->when(!is_null($search->getSearchAsString()), function (Builder $builder) {
                /** @var ColumnsHelper */
                $columnsHelper = App::make(ColumnsHelper::class);

                foreach ($this->searchable as $column) {
                    $builder = $builder->orderByRaw("{$columnsHelper->getColumnWithSnakes($column . '_relevance')} DESC");
                }

                return $builder;
            });
        });
    }

    public function scopeFilterResult(Builder $builder, Paginate|Get|null $result = null): LengthAwarePaginator|Collection|Builder
    {
        if ($result instanceof Paginate) {
            return $this->scopeFilterPaginate($builder, $result);
        }

        if ($result instanceof Get) {
            return $this->scopeFilterGet($builder, $result);
        }

        return $builder;
    }

    public function scopeFilterPaginate(Builder $builder, Paginate $paginate): LengthAwarePaginator
    {
        return $builder->paginate(
            perPage: $paginate->perPage ?? Config::get('database.paginate'),
            page: $paginate->page
        );
    }

    public function scopeFilterGet(Builder $builder, Get $get): Collection
    {
        return $builder->when(!is_null($get->take), function (Builder $builder) use ($get) {
            return $builder->take($get->take); //@phpstan-ignore-line
        })
        ->get();
    }

    public function scopeFilterOrderBy(Builder $builder, ?OrderBy $orderby = null): Builder
    {
        return $builder->when(!is_null($orderby), function (Builder $builder) use ($orderby) {
            /** @var OrderBy $orderby */
            return $builder->orderBy($orderby->attribute, $orderby->order->value);
        });
    }

    public function scopeFilterExcept(Builder $builder, ?array $except = null): Builder
    {
        return $builder->when(!is_null($except), function (Builder $builder) use ($except) {
            $builder->whereNotIn("{$this->getTable()}.{$this->getKeyName()}", $except);
        });
    }
}
