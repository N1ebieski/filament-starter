<?php

declare(strict_types=1);

namespace App\Queries\Role\GetByFilter;

use App\Queries\Query;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Queries\Result\Get;
use App\Queries\Result\Paginate;
use App\Data\Casts\OrderBy\OrderByCast;
use App\Queries\Result\ResultInterface;
use App\Data\Casts\Paginate\PaginateCast;
use App\Data\Casts\SearchBy\SearchByCast;
use App\Queries\SearchBy\SearchByInterface;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;

#[Handler(\App\Queries\Role\GetByFilter\GetByFilterHandler::class)]
final class GetByFilterQuery extends Query implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Role $role = new Role(),
        #[WithCast(SearchByCast::class, Role::class)]
        public readonly ?SearchByInterface $searchBy = null,
        public readonly ?array $except = null,
        #[WithCast(OrderByCast::class)]
        public readonly ?OrderBy $orderBy = null,
        #[WithCast(PaginateCast::class)]
        public readonly ?ResultInterface $result = null
    ) {
    }
}
