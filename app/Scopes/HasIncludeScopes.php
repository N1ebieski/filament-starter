<?php

declare(strict_types=1);

namespace App\Scopes;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Support\Query\Columns\ColumnsHelper;
use App\Support\Query\Includes\IncludesHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @mixin Model
 */
trait HasIncludeScopes
{
    public function scopeFilterInclude(Builder $builder, ?array $include): Builder
    {
        return $builder->when(!is_null($include), function (Builder $builder) use ($include): Builder {
            /** @var array<string, array> $include */

            foreach ($include as $relation => $columns) {
                $scopeName = IncludesHelper::getScopeRelationName($relation);

                if (method_exists($this, $scopeName)) {
                    $builder->{$scopeName}($builder, $columns);

                    continue;
                }

                $table = Str::afterLast($relation, '.');

                $columnsWithTablePrefix = ColumnsHelper::getColumnsWithTablePrefix($columns, $table);

                $builder->with([$relation => fn (Builder $builder): Builder => $builder->select($columnsWithTablePrefix)]);
            }

            return $builder;
        });
    }
}
