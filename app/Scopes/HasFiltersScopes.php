<?php

declare(strict_types=1);

namespace App\Scopes;

use App\Queries\OrderBy;
use App\Scopes\HasSearchScopes;
use Illuminate\Support\Facades\Config;
use App\Support\Query\Columns\ColumnsHelper;
use Illuminate\Database\Eloquent\Collection;
use App\Queries\Shared\Result\Drivers\Get\Get;
use App\Queries\Shared\Result\ResultInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Queries\Shared\Result\Drivers\Paginate\Paginate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Queries\Shared\Result\Drivers\DriverHandlerFactory;

/**
 * @mixin FiltersScopesInterface
 */
trait HasFiltersScopes
{
    use HasSearchScopes;
    use HasIncludeScopes;

    public function scopeFilterResult(Builder $builder, ?ResultInterface $result): LengthAwarePaginator|Collection|Builder
    {
        return $builder->when(!is_null($result), function (Builder $builder) use ($result): Builder {
            /** @var ResultInterface $result */

            $handler = DriverHandlerFactory::makeHandler($result, $builder);

            return $handler->handle($result);
        });
    }

    public function scopeFilterPaginate(Builder $builder, Paginate $paginate): LengthAwarePaginator
    {
        return $builder->paginate(
            perPage: $paginate->perPage ?? Config::get('database.paginate'),
            page: $paginate->page
        );
    }

    public function scopeFilterGet(Builder $builder, Get $get): Collection
    {
        return $builder->when(!is_null($get->take), function (Builder $builder) use ($get): Builder {
            /** @var int */
            $take = $get->take;

            return $builder->take($take);
        })
        ->get();
    }

    public function scopeFilterOrderBy(Builder $builder, ?OrderBy $orderBy): Builder
    {
        return $builder->when(!is_null($orderBy), function (Builder $builder) use ($orderBy): Builder {
            /** @var OrderBy $orderBy */

            return $builder->orderBy($orderBy->attribute, $orderBy->order->value);
        });
    }

    public function scopeFilterSelect(Builder $builder, ?array $select): Builder
    {
        return $builder->when(!is_null($select), function (Builder $builder) use ($select): Builder {
            /** @var array $select */

            $selectsWithTablePrefix = ColumnsHelper::getColumnsWithTablePrefix($select, $this->getTable());

            return $builder->select($selectsWithTablePrefix);
        }, function (Builder $builder): Builder {
            return $builder->selectRaw("`{$this->getTable()}`.*");
        });
    }

    public function scopeFilterIgnore(Builder $builder, ?array $ignore): Builder
    {
        return $builder->when(!is_null($ignore), function (Builder $builder) use ($ignore): Builder {
            return $builder->whereNotIn("{$this->getTable()}.{$this->getKeyName()}", $ignore);
        });
    }
}
