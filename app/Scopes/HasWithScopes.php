<?php

declare(strict_types=1);

namespace App\Scopes;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Support\Query\Include\IncludeHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @mixin Model
 */
trait HasWithScopes
{
    public function scopeFilterWith(Builder $builder, ?array $with): Builder
    {
        return $builder->when(!is_null($with), function (Builder $builder) use ($with): Builder {
            /** @var array<int, string> $with */

            foreach ($with as $relation) {
                $scopeName = IncludeHelper::getScopeRelationName($relation);

                if (method_exists($this, $scopeName)) {
                    $columnsAsArray = Str::contains($relation, ':') ? Str::of($relation)
                        ->afterLast(':')
                        ->explode(',')
                        ->map(fn (string $column): string => trim($column))
                        ->toArray() : [];

                    $builder->{$scopeName}($builder, $columnsAsArray);

                    continue;
                }

                $builder->with($relation);
            }

            return $builder;
        }, function (Builder $builder): Builder {
            $builder = $this->scopeWithAll($builder);

            return $builder;
        });
    }

    public function scopeWithAll(Builder $builder): Builder
    {
        return $builder;
    }
}
