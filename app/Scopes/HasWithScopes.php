<?php

declare(strict_types=1);

namespace App\Scopes;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\HasAttributesInterface;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use App\Support\Query\Columns\ColumnsHelper;
use App\Support\Query\Include\IncludeHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @mixin Model
 */
trait HasWithScopes
{
    public function scopeFilterWith(Builder $builder, ?array $with, bool $withAll = false): Builder
    {
        return $builder->when(!is_null($with), function (Builder $builder) use ($with): Builder {
            /** @var array<int, string> $with */

            foreach ($with as $baseRelation) {
                $scopeName = IncludeHelper::getScopeRelationName($baseRelation);

                $realRelation = Str::beforeLast($baseRelation, ':');

                $columnsAsCollection = Str::contains($baseRelation, ':') ? Str::of($baseRelation)
                    ->afterLast(':')
                    ->explode(',')
                    ->map(fn (string $column): string => trim($column)) : new Collection();

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
                        if ($model instanceof HasAttributesInterface) {
                            $columnsAsCollection->push(...$model->getSelectAlways());
                        }

                        return $builder->select(ColumnsHelper::getColumnsWithTablePrefix($columnsAsCollection->toArray(), $model->getTable()));
                    });
                }]);
            }

            return $builder;
        }, function (Builder $builder) use ($withAll): Builder {
            if ($withAll) {
                $builder = $this->scopeWithAll($builder);
            }

            return $builder;
        });
    }

    public function scopeWithAll(Builder $builder): Builder
    {
        return $builder;
    }
}
