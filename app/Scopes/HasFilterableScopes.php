<?php

declare(strict_types=1);

namespace App\Scopes;

use App\Queries\Get;
use App\Queries\OrderBy;
use App\Queries\Paginate;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait HasFilterableScopes
{
    use HasSearchScopes;

    public function scopeFilterResult(Builder $builder, Paginate|Get|null $result): LengthAwarePaginator|Collection|Builder
    {
        if ($result instanceof Paginate) {
            return $this->scopeFilterPaginate($builder, $result);
        }

        if ($result instanceof Get) {
            return $this->scopeFilterGet($builder, $result);
        }

        return $builder;
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

    public function scopeFilterOrderBy(Builder $builder, ?OrderBy $orderby): Builder
    {
        return $builder->when(!is_null($orderby), function (Builder $builder) use ($orderby) {
            /** @var OrderBy $orderby */
            return $builder->orderBy($orderby->attribute, $orderby->order->value);
        });
    }

    public function scopeFilterExcept(Builder $builder, ?array $except): Builder
    {
        return $builder->when(!is_null($except), function (Builder $builder) use ($except) {
            $builder->whereNotIn("{$this->getTable()}.{$this->getKeyName()}", $except);
        });
    }
}
