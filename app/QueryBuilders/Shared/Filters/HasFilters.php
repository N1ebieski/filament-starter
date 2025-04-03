<?php

declare(strict_types=1);

namespace App\QueryBuilders\Shared\Filters;

use App\Models\Shared\Attributes\AttributesInterface;
use App\Queries\Shared\OrderBy\OrderBy;
use App\Queries\Shared\Result\Drivers\DriverHandlerFactory;
use App\Queries\Shared\Result\Drivers\Get\Get;
use App\Queries\Shared\Result\Drivers\Paginate\Paginate;
use App\Queries\Shared\Result\ResultInterface;
use App\QueryBuilders\Shared\Search\HasSearch;
use App\QueryBuilders\Shared\With\HasWith;
use App\Support\Query\Columns\ColumnsHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

/**
 * @mixin Builder
 */
trait HasFilters
{
    use HasSearch;
    use HasWith;

    public function filterResult(?ResultInterface $result): LengthAwarePaginator|Collection|Builder
    {
        return $this->when(! is_null($result), function (Builder $builder) use ($result): LengthAwarePaginator|Collection|Builder {
            /** @var ResultInterface $result */
            $handler = DriverHandlerFactory::makeHandler($result, $builder);

            return $handler->handle($result);
        });
    }

    public function filterPaginate(Paginate $paginate): LengthAwarePaginator
    {
        return $this->paginate(
            perPage: $paginate->perPage,
            page: $paginate->page
        );
    }

    public function filterGet(Get $get): Collection
    {
        return $this->when(! is_null($get->take), function (Builder $builder) use ($get): Builder {
            /** @var int */
            $take = $get->take;

            return $builder->take($take);
        })->get();
    }

    public function filterOrderBy(?OrderBy $orderBy): self
    {
        return $this->when(! is_null($orderBy), function (Builder $builder) use ($orderBy): Builder {
            /** @var OrderBy $orderBy */
            return $builder->orderBy($orderBy->attribute, $orderBy->order->value);
        });
    }

    public function filterSelect(?array $select): self
    {
        return $this->when(! is_null($select), function (Builder $builder) use ($select): Builder {
            /** @var array $select */
            $model = $builder->getModel();

            $columnsAsCollection = SupportCollection::make($select);

            /**
             * We need to select all foreign keys just in case
             */
            if ($model instanceof AttributesInterface) {
                $columnsAsCollection->push(...$model->selectAlways);
            }

            $columnsWithTablePrefix = ColumnsHelper::getColumnsWithTablePrefix($columnsAsCollection->toArray(), $model->getTable());

            return $builder->select($columnsWithTablePrefix);
        }, function (Builder $builder): Builder {
            $model = $this->getModel();

            return $builder->selectRaw("`{$model->getTable()}`.*");
        });
    }

    public function filterIgnore(?array $ignore): self
    {
        return $this->when(! is_null($ignore), function (Builder $builder) use ($ignore): Builder {
            $model = $this->getModel();

            return $builder->whereNotIn("{$model->getTable()}.{$model->getKeyName()}", $ignore);
        });
    }
}
