<?php

declare(strict_types=1);

namespace App\Scopes\Search;

use Laravel\Scout\Searchable;
use App\Queries\SearchBy\SearchBy;
use Illuminate\Support\Facades\App;
use App\Queries\SearchBy\DatabaseMatch;
use Illuminate\Database\Eloquent\Model;
use App\Support\Query\Columns\ColumnsHelper;
use App\Queries\SearchBy\DatabaseMatchFactory;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property array<string> $searchableAttributes
 * @method \Illuminate\Database\Eloquent\Builder|HasSearchScopes filterOrderByDatabaseMatch(?\App\Queries\Search\DatabaseMatch $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder|HasSearchScopes filterSearchByScout(?\App\Queries\SearchBy\SearchBy $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder|HasSearchScopes filterSearchByDatabaseMatch(?\App\Queries\Search\DatabaseMatch $databaseMatch, string $boolean = 'and')
 * @method \Illuminate\Database\Eloquent\Builder|HasSearchScopes filterSearchAttributesByDatabaseMatch(?\App\Queries\Search\DatabaseMatch $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder|HasSearchScopes filterOrderByDatabaseMatch(?\App\Queries\OrderBy $orderby)
 * @mixin Model
 */
trait HasSearchScopes
{
    use Searchable;

    public function scopeFilterSearchBy(
        Builder|HasSearchScopes $builder,
        ?SearchBy $searchBy,
        bool $isOrderBy,
        Driver $driver = Driver::DatabaseMatch
    ): Builder {
        return $builder->when(!is_null($searchBy), function (Builder|HasSearchScopes $builder) use ($searchBy, $isOrderBy, $driver) {
            return match ($driver) {
                Driver::Scout => $builder->filterSearchByScout($searchBy),

                Driver::DatabaseMatch => tap($builder, function (Builder $builder) use ($searchBy, $isOrderBy) {
                    $databaseMatch = DatabaseMatchFactory::makeDatabaseMatch(
                        term: $searchBy->term,
                        model: $builder->getModel()
                    );

                    return $builder->filterSearchByDatabaseMatch($databaseMatch)
                        ->filterSearchAttributesByDatabaseMatch($databaseMatch)
                        ->when($isOrderBy, function (Builder|HasSearchScopes $builder) use ($databaseMatch) {
                            return $builder->filterOrderByDatabaseMatch($databaseMatch);
                        });
                })
            };
        });
    }

    public function scopeFilterSearchAttributesByDatabaseMatch(Builder $builder, ?DatabaseMatch $databaseMatch): Builder
    {
        return $builder->when(!is_null($databaseMatch), function (Builder $builder) use ($databaseMatch) {
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
        });
    }

    public function scopeFilterSearchByScout(Builder $builder, ?SearchBy $searchby): Builder
    {
        return $builder->when(!is_null($searchby), function (Builder $builder) use ($searchby) {
            $ids = $this->search($searchby->term)->get()->pluck($this->getKeyName());

            return $builder->whereIn("{$this->getTable()}.{$this->getKeyName()}", $ids->toArray());
        });
    }

    public function scopeFilterSearchByDatabaseMatch(Builder $builder, ?DatabaseMatch $databaseMatch, string $boolean = 'and'): Builder
    {
        return $builder->when(!is_null($databaseMatch), function (Builder $builder) use ($databaseMatch, $boolean) {
            /** @var DatabaseMatch $search */
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
        });
    }

    public function scopeFilterOrderByDatabaseMatch(Builder $builder, ?DatabaseMatch $databaseMatch): Builder
    {
        return $builder->when(!is_null($databaseMatch), function (Builder $builder) use ($databaseMatch) {
            /** @var DatabaseMatch $search */
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
        });
    }
}
