<?php

declare(strict_types=1);

namespace App\Scopes;

use Laravel\Scout\Searchable;
use App\Models\HasScoutSearchable;
use Illuminate\Database\Eloquent\Model;
use App\Models\HasDatabaseMatchSearchable;
use App\Support\Query\Columns\ColumnsHelper;
use App\Queries\Shared\SearchBy\SearchByInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Queries\Shared\SearchBy\Drivers\Scout\Scout;
use App\Queries\Shared\SearchBy\Drivers\DriverHandlerFactory;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch;

/**
 * @mixin Model
 * @mixin Searchable
 * @mixin HasDatabaseMatchSearchable
 * @mixin HasScoutSearchable
 * @mixin SearchScopesInterface
 */
trait HasSearchScopes
{
    public function scopeFilterSearchBy(Builder $builder, ?SearchByInterface $searchBy): Builder
    {
        return $builder->when(!is_null($searchBy), function (Builder $builder) use ($searchBy) {
            $handlerFactory = DriverHandlerFactory::makeHandler($searchBy, $builder);

            /** @disregard */
            return $handlerFactory->handle($searchBy);
        });
    }

    public function scopeFilterSearchAttributesByDatabaseMatch(Builder $builder, DatabaseMatch $databaseMatch): Builder
    {
        /** @var DatabaseMatch $search */
        return $builder->when(!is_null($databaseMatch->attributes), function (Builder $builder) use ($databaseMatch) {
            /** @var array */
            $attributes = $databaseMatch->attributes;

            return $builder->where(function (Builder $builder) use ($attributes) {
                foreach ($this->getSearchableAttributes() as $attr) {
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
        $ids = $this->search($scout->query, $scout->callback)->take($scout->get->take)->keys();

        return $builder->whereIn("{$this->getTable()}.{$this->getKeyName()}", $ids->toArray());
    }

    public function scopeFilterSearchByDatabaseMatch(Builder $builder, DatabaseMatch $databaseMatch, string $boolean = 'and'): Builder
    {
        return $builder->when(!is_null($databaseMatch->getSearchAsString()), function (Builder $builder) use ($databaseMatch, $boolean) {
            $table = $this->getTable();

            $columns = ColumnsHelper::getColumnsWithTablePrefix($this->getSearchable(), $table);

            $columnsAsString = ColumnsHelper::getColumnsAsString($columns);

            $builder = $builder->whereRaw(
                "MATCH ({$columnsAsString}) AGAINST (? IN BOOLEAN MODE)",
                [$databaseMatch->getSearchAsString()],
                $boolean
            );

            foreach ($columns as $column) {
                $columnWithTicks = ColumnsHelper::getColumnWithTicks($column);

                $columnWithSnakes = ColumnsHelper::getColumnWithSnakes($column . '_relevance');

                $builder = $builder->selectRaw(
                    "MATCH ({$columnWithTicks}) AGAINST (? IN BOOLEAN MODE) AS {$columnWithSnakes}",
                    [$databaseMatch->getSearchAsString()]
                );
            }

            return $builder;
        });
    }

    public function scopeFilterOrderByDatabaseMatch(Builder $builder, DatabaseMatch $databaseMatch): Builder
    {
        return $builder->when(!is_null($databaseMatch->getSearchAsString()), function (Builder $builder) {
            $table = $this->getTable();

            $columns = ColumnsHelper::getColumnsWithTablePrefix($this->getSearchable(), $table);

            foreach ($columns as $column) {
                $columnWithSnakes = ColumnsHelper::getColumnWithSnakes($column . '_relevance');

                $builder = $builder->orderByRaw("{$columnWithSnakes} DESC");
            }

            return $builder;
        });
    }
}
