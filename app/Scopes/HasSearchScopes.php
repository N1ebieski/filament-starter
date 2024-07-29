<?php

declare(strict_types=1);

namespace App\Scopes;

use App\Queries\Search;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use App\Support\Query\Columns\ColumnsHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property array<string> $searchableAttributes
 * @mixin Model
 */
trait HasSearchScopes
{
    public function scopeFilterSearchAttributes(Builder $builder, ?Search $search): Builder
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

    public function scopeFilterSearch(Builder $builder, ?Search $search, string $boolean = 'and'): Builder
    {
        return $builder->when(!is_null($search), function (Builder $builder) use ($search, $boolean) {
            /** @var Search $search */
            return $builder->when(!is_null($search->getSearchAsString()), function (Builder $builder) use ($search, $boolean) {
                /** @var ColumnsHelper */
                $columnsHelper = App::make(ColumnsHelper::class);

                $table = $this->getTable();

                $columns = $columnsHelper->getColumnsWithTablePrefix($this->searchable, $table);

                $builder = $builder->whereRaw(
                    "MATCH ({$columnsHelper->getColumnsAsString($columns)}) AGAINST (? IN BOOLEAN MODE)",
                    [$search->getSearchAsString()],
                    $boolean
                );

                foreach ($columns as $column) {
                    $builder = $builder->selectRaw(
                        "MATCH ({$columnsHelper->getColumnWithTicks($column)}) AGAINST (? IN BOOLEAN MODE) AS {$columnsHelper->getColumnWithSnakes($column . '_relevance')}",
                        [$search->getSearchAsString()]
                    );
                }

                return $builder;
            });
        });
    }

    public function scopeFilterOrderBySearch(Builder $builder, ?Search $search): Builder
    {
        return $builder->when(!is_null($search), function (Builder $builder) use ($search) {
            /** @var Search $search */
            return $builder->when(!is_null($search->getSearchAsString()), function (Builder $builder) {
                /** @var ColumnsHelper */
                $columnsHelper = App::make(ColumnsHelper::class);

                $table = $this->getTable();

                $columns = $columnsHelper->getColumnsWithTablePrefix($this->searchable, $table);

                foreach ($columns as $column) {
                    $builder = $builder->orderByRaw("{$columnsHelper->getColumnWithSnakes($column . '_relevance')} DESC");
                }

                return $builder;
            });
        });
    }
}
