<?php

declare(strict_types=1);

namespace App\Scopes;

use Laravel\Scout\Searchable;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use App\Queries\SearchBy\SearchByInterface;
use App\Support\Query\Columns\ColumnsHelper;
use App\Queries\SearchBy\Drivers\Scout\Scout;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Queries\SearchBy\Drivers\DatabaseMatch\DatabaseMatch;

/**
 * @property array<string> $searchableAttributes
 * @method \Illuminate\Database\Eloquent\Builder|HasSearchScopes filterOrderByDatabaseMatch(App\Queries\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder|HasSearchScopes filterSearchByScout(App\Queries\SearchBy\Drivers\Scout\Scout $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder|HasSearchScopes filterSearchByDatabaseMatch(App\Queries\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch, string $boolean = 'and')
 * @method \Illuminate\Database\Eloquent\Builder|HasSearchScopes filterSearchAttributesByDatabaseMatch(App\Queries\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder|HasSearchScopes filterOrderByDatabaseMatch(App\Queries\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @mixin Model
 */
trait HasSearchScopes
{
    use Searchable;

    public function scopeFilterSearchBy(
        Builder|HasSearchScopes $builder,
        ?SearchByInterface $searchBy
    ): Builder {
        return $builder->when(!is_null($searchBy), function (Builder|HasSearchScopes $builder) use ($searchBy) {
            return match (true) {
                $searchBy instanceof Scout => $builder->filterSearchByScout($searchBy),

                $searchBy instanceof DatabaseMatch => value(function (Builder $builder) use ($searchBy) {
                    return $builder->filterSearchByDatabaseMatch($searchBy)
                        ->filterSearchAttributesByDatabaseMatch($searchBy)
                        ->when($searchBy->isOrderBy, function (Builder|HasSearchScopes $builder) use ($searchBy) {
                            return $builder->filterOrderByDatabaseMatch($searchBy);
                        });
                }, $builder)
            };
        });
    }

    public function scopeFilterSearchAttributesByDatabaseMatch(Builder $builder, DatabaseMatch $databaseMatch): Builder
    {
        /** @var DatabaseMatch $search */
        return $builder->when(!is_null($databaseMatch->attributes), function (Builder $builder) use ($databaseMatch) {
            /** @var array */
            $attributes = $databaseMatch->attributes;

            return $builder->where(function (Builder $builder) use ($attributes) {
                foreach ($this->searchableAttributes as $attr) {
                    $builder = $builder->when(array_key_exists($attr, $attributes), function (Builder $builder) use ($attr, $attributes) {
                        return $builder->where("{$this->getTable()}.{$attr}", $attributes[$attr]);
                    });
                }

                return $builder;
            });
        });
    }

    public function scopeFilterSearchByScout(Builder $builder, Scout $scout): Builder
    {
        $ids = $this->search(
            query: $scout->query,
            callback: $scout->callback
        )->get()->pluck($this->getKeyName());

        return $builder->whereIn("{$this->getTable()}.{$this->getKeyName()}", $ids->toArray());
    }

    public function scopeFilterSearchByDatabaseMatch(Builder $builder, DatabaseMatch $databaseMatch, string $boolean = 'and'): Builder
    {
        return $builder->when(!is_null($databaseMatch->getSearchAsString()), function (Builder $builder) use ($databaseMatch, $boolean) {
            /** @var ColumnsHelper */
            $columnsHelper = App::make(ColumnsHelper::class);

            $table = $this->getTable();

            $columns = $columnsHelper->getColumnsWithTablePrefix($this->searchable, $table);

            $builder = $builder->whereRaw(
                "MATCH ({$columnsHelper->getColumnsAsString($columns)}) AGAINST (? IN BOOLEAN MODE)",
                [$databaseMatch->getSearchAsString()],
                $boolean
            );

            foreach ($columns as $column) {
                $builder = $builder->selectRaw(
                    "MATCH ({$columnsHelper->getColumnWithTicks($column)}) AGAINST (? IN BOOLEAN MODE) AS {$columnsHelper->getColumnWithSnakes($column . '_relevance')}",
                    [$databaseMatch->getSearchAsString()]
                );
            }

            return $builder;
        });
    }

    public function scopeFilterOrderByDatabaseMatch(Builder $builder, DatabaseMatch $databaseMatch): Builder
    {
        return $builder->when(!is_null($databaseMatch->getSearchAsString()), function (Builder $builder) {
            /** @var ColumnsHelper */
            $columnsHelper = App::make(ColumnsHelper::class);

            $table = $this->getTable();

            $columns = $columnsHelper->getColumnsWithTablePrefix($this->searchable, $table);

            foreach ($columns as $column) {
                $builder = $builder->orderByRaw("{$columnsHelper->getColumnWithSnakes($column . '_relevance')} DESC");
            }

            return $builder;
        });
    }
}
