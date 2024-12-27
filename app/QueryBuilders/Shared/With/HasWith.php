<?php

declare(strict_types=1);

namespace App\QueryBuilders\Shared\With;

use App\Models\Shared\Attributes\AttributesInterface;
use App\Support\Query\Columns\ColumnsHelper;
use App\Support\Query\Include\IncludeHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @mixin Builder
 */
trait HasWith
{
    public function filterWith(?array $with, bool $withAll = false): self
    {
        return $this->when(! is_null($with), function (Builder $builder) use ($with): Builder {
            /** @var array<int, string> $with */
            foreach ($with as $baseRelation) {
                $scopeName = IncludeHelper::getBuilderRelationName($baseRelation);

                $realRelation = Str::beforeLast($baseRelation, ':');

                $columnsAsCollection = Str::contains($baseRelation, ':') ? Str::of($baseRelation)
                    ->afterLast(':')
                    ->explode(',')
                    ->map(fn (string $column): string => trim($column)) : new Collection;

                if (method_exists($this, $scopeName)) {
                    $builder->{$scopeName}($builder, $columnsAsCollection->toArray());

                    continue;
                }

                $builder->with([$realRelation => function (Builder $builder) use ($columnsAsCollection) {
                    return $builder->when($columnsAsCollection->isNotEmpty(), function (Builder $builder) use ($columnsAsCollection): Builder {
                        $model = $builder->getModel();

                        /**
                         * We need to select all foreign keys just in case
                         */
                        if ($model instanceof AttributesInterface) {
                            $columnsAsCollection->push(...$model->selectAlways);
                        }

                        return $builder->select(ColumnsHelper::getColumnsWithTablePrefix($columnsAsCollection->toArray(), $model->getTable()));
                    });
                }]);
            }

            return $builder;
        }, function (Builder $builder) use ($withAll): Builder {
            if ($withAll) {
                $builder = $this->withAll();
            }

            return $builder;
        });
    }

    public function withAll(): self
    {
        return $this;
    }
}
