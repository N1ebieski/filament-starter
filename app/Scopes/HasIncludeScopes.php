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
trait HasIncludeScopes
{
    public function scopeFilterInclude(Builder $builder, ?array $include): Builder
    {
        return $builder->when(!is_null($include), function (Builder $builder) use ($include): Builder {
            /** @var array<int, string> $include */

            foreach ($include as $relation) {
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
        });
    }
}
