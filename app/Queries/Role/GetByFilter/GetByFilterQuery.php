<?php

declare(strict_types=1);

namespace App\Queries\Role\GetByFilter;

use App\Queries\Get;
use App\Queries\Query;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Queries\Paginate;
use App\Queries\Search\Search;
use App\Data\Casts\Search\SearchCast;
use App\Data\Casts\OrderBy\OrderByCast;
use App\Data\Casts\Paginate\PaginateCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;

#[Handler(\App\Queries\Role\GetByFilter\GetByFilterHandler::class)]
final class GetByFilterQuery extends Query implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Role $role = new Role(),
        #[WithCast(SearchCast::class, Role::class)]
        public readonly ?Search $search = null,
        public readonly ?array $except = null,
        #[WithCast(OrderByCast::class)]
        public readonly ?OrderBy $orderby = null,
        #[WithCast(PaginateCast::class)]
        public readonly Paginate|Get|null $result = null
    ) {
    }
}
