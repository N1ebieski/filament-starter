<?php

declare(strict_types=1);

namespace App\QueryBuilders\Shared\Search;

use App\Models\Model;
use App\Models\Shared\Searchable\ScoutSearchableInterface;
use App\Models\Shared\Searchable\SearchableInterface;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch;
use App\Queries\Shared\SearchBy\Drivers\DriverHandlerFactory;
use App\Queries\Shared\SearchBy\Drivers\Scout\Scout;
use App\Queries\Shared\SearchBy\SearchByInterface;
use App\Support\Query\Columns\ColumnsHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Scout\Builder as ScoutBuilder;

/**
 * @mixin Builder
 */
trait HasSearch
{
    public function filterSearchBy(?SearchByInterface $searchBy): self
    {
        /** @var self */
        return $this->when(! is_null($searchBy), function (Builder $builder) use ($searchBy): Builder {
            /** @var SearchByInterface $searchBy */
            $handler = DriverHandlerFactory::makeHandler($searchBy, $builder);

            return $handler->handle($searchBy);
        });
    }

    public function filterSearchAttributesByDatabaseMatch(DatabaseMatch $databaseMatch): self
    {
        return $this->when(! is_null($databaseMatch->attributes), function (Builder $builder) use ($databaseMatch): Builder {
            /** @var array $attributes */
            $attributes = $databaseMatch->attributes;

            return $builder->where(function (Builder $builder) use ($attributes) {
                /** @var Model&SearchableInterface $model */
                $model = $this->getModel();

                foreach ($model->searchableAttributes as $attr) {
                    $builder = $builder->when(array_key_exists($attr, $attributes), fn (Builder $builder) => $builder
                        ->where("{$model->getTable()}.{$attr}", $attributes[$attr])
                    );
                }

                return $builder;
            });
        });
    }

    public function filterSearchByScout(Scout $scout): self
    {
        /** @var Model&ScoutSearchableInterface */
        $model = $this->getModel();

        $ids = $model->search($scout->query, $scout->callback)
            ->unless(is_null($scout->get->take), function (ScoutBuilder $builder) use ($scout): ScoutBuilder {
                /** @var int */
                $take = $scout->get->take;

                return $builder->take($take);
            })
            ->keys();

        return $this->whereIn("{$model->getTable()}.{$model->getKeyName()}", $ids->toArray());
    }

    public function filterSearchByDatabaseMatch(DatabaseMatch $databaseMatch, string $boolean = 'and'): self
    {
        return $this->when(! is_null($databaseMatch->getSearchAsString()), function (Builder $builder) use ($databaseMatch, $boolean): Builder {
            /** @var Model&SearchableInterface */
            $model = $builder->getModel();

            $table = $model->getTable();

            $columns = ColumnsHelper::getColumnsWithTablePrefix($model->searchable, $table);

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

    public function filterOrderByDatabaseMatch(DatabaseMatch $databaseMatch): self
    {
        return $this->when(! is_null($databaseMatch->getSearchAsString()), function (Builder $builder): Builder {
            /** @var Model&SearchableInterface */
            $model = $this->getModel();

            $table = $model->getTable();

            $columns = ColumnsHelper::getColumnsWithTablePrefix($model->searchable, $table);

            foreach ($columns as $column) {
                $columnWithSnakes = ColumnsHelper::getColumnWithSnakes($column.'_relevance');

                $builder = $builder->orderByRaw("{$columnWithSnakes} DESC");
            }

            return $builder;
        });
    }
}
