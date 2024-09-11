<?php

declare(strict_types=1);

namespace App\Scopes;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Support\Query\Columns\ColumnsHelper;
use App\Support\Query\Includes\IncludesHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @mixin Model
 */
trait HasIncludesScopes
{
    public function scopeFilterIncludes(Builder $builder, ?array $includes): Builder
    {
        return $builder->when(!is_null($includes), function (Builder $builder) use ($includes): Builder {
            /** @var array<int|string, string|array> $includes */

            /** @var array<string, array> $includesWithKeys */
            $includesWithKeys = Collection::make($includes)
                ->mapWithKeys(function (string|array $selects, int|string $relation): array {
                    if (is_int($relation) && is_string($selects)) {
                        return [$selects => ['*']];
                    }

                    $selectsAsArray = is_string($selects) ? explode(', ', $selects) : $selects;

                    return [$relation => $selectsAsArray];
                })
                ->toArray();

            foreach ($includesWithKeys as $relation => $selects) {
                $scopeName = IncludesHelper::getScopeRelationName($relation);

                if (method_exists($this, $scopeName)) {
                    $builder->{$scopeName}($builder, $selects);

                    continue;
                }

                $table = Str::afterLast($relation, '.');

                $selectsAsArrayWithTablePrefix = ColumnsHelper::getColumnsWithTablePrefix($selects, $table);

                $builder->with([$relation => fn (Builder $builder): Builder => $builder->select($selectsAsArrayWithTablePrefix)]);
            }

            return $builder;
        });
    }
}
