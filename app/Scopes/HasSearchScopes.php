<?php

declare(strict_types=1);

namespace App\Scopes;

use App\Models\HasDatabaseMatchSearchable;
use App\Models\HasScoutSearchable;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch;
use App\Queries\Shared\SearchBy\Drivers\DriverHandlerFactory;
use App\Queries\Shared\SearchBy\Drivers\Scout\Scout;
use App\Queries\Shared\SearchBy\SearchByInterface;
use App\Support\Query\Columns\ColumnsHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Builder as ScoutBuilder;
use Laravel\Scout\Searchable;

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
        return $builder->when(! is_null($searchBy), function (Builder $builder) use ($searchBy): Builder {
            /** @var SearchByInterface $searchBy */
            $handler = DriverHandlerFactory::makeHandler($searchBy, $builder);

            return $handler->handle($searchBy);
        });
    }

    public function scopeFilterSearchAttributesByDatabaseMatch(Builder $builder, DatabaseMatch $databaseMatch): Builder
    {
        return $builder->when(! is_null($databaseMatch->attributes), function (Builder $builder) use ($databaseMatch): Builder {
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
        $ids = $this->search($scout->query, $scout->callback)
            ->when(! is_null($scout->get->take), function (ScoutBuilder $builder) use ($scout): ScoutBuilder {
                /** @var int */
                $take = $scout->get->take;

                return $builder->take($take);
            })
            ->keys();

        return $builder->whereIn("{$this->getTable()}.{$this->getKeyName()}", $ids->toArray());
    }

    public function scopeFilterSearchByDatabaseMatch(Builder $builder, DatabaseMatch $databaseMatch, string $boolean = 'and'): Builder
    {
        return $builder->when(! is_null($databaseMatch->getSearchAsString()), function (Builder $builder) use ($databaseMatch, $boolean): Builder {
            $table = $this->getTable();

            $columns = ColumnsHelper::getColumnsWithTablePrefix($this->searchable, $table);

            $columnsAsString = ColumnsHelper::getColumnsAsString($columns);

            $builder = $builder->whereRaw(
                "MATCH ({$columnsAsString}) AGAINST (? IN BOOLEAN MODE)",
                [$databaseMatch->getSearchAsString()],
                $boolean
            );

            foreach ($columns as $column) {
                $columnWithTicks = ColumnsHelper::getColumnWithTicks($column);

                $columnWithSnakes = ColumnsHelper::getColumnWithSnakes($column.'_relevance');

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
        return $builder->when(! is_null($databaseMatch->getSearchAsString()), function (Builder $builder): Builder {
            $table = $this->getTable();

            $columns = ColumnsHelper::getColumnsWithTablePrefix($this->searchable, $table);

            foreach ($columns as $column) {
                $columnWithSnakes = ColumnsHelper::getColumnWithSnakes($column.'_relevance');

                $builder = $builder->orderByRaw("{$columnWithSnakes} DESC");
            }

            return $builder;
        });
    }
}
