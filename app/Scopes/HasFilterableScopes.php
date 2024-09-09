<?php

declare(strict_types=1);

namespace App\Scopes;

use App\Queries\OrderBy;
use App\Queries\Result\Get;
use App\Scopes\HasSearchScopes;
use App\Queries\Result\Paginate;
use Illuminate\Support\Facades\Config;
use App\Queries\Result\ResultInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
* @method \Illuminate\Contracts\Pagination\LengthAwarePaginator filterPaginate(App\Queries\Result\Paginate $paginate)
 * @method \Illuminate\Database\Eloquent\Collection filterGet(App\Queries\Result\Get $get)
 */
trait HasFilterableScopes
{
    use HasSearchScopes;

    public function scopeFilterResult(Builder $builder, ?ResultInterface $result): LengthAwarePaginator|Collection|Builder
    {
        return $builder->when(!is_null($result), function (Builder $builder) use ($result) {
            return $result->getResultBuilder($builder);
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
        return $builder->when(!is_null($get->take), function (Builder $builder) use ($get) {
            return $builder->take($get->take); //@phpstan-ignore-line
        })
        ->get();
    }

    public function scopeFilterOrderBy(Builder $builder, ?OrderBy $orderBy): Builder
    {
        return $builder->when(!is_null($orderBy), function (Builder $builder) use ($orderBy) {
            /** @var OrderBy $orderby */
            return $builder->orderBy($orderBy->attribute, $orderBy->order->value);
        });
    }

    public function scopeFilterExcept(Builder $builder, ?array $except): Builder
    {
        return $builder->when(!is_null($except), function (Builder $builder) use ($except) {
            $builder->whereNotIn("{$this->getTable()}.{$this->getKeyName()}", $except);
        });
    }
}
