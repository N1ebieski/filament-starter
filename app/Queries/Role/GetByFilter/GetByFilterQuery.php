<?php

declare(strict_types=1);

namespace App\Queries\Role\GetByFilter;

use App\Queries\Query;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Data\Casts\Select\SelectCast;
use App\Data\Casts\OrderBy\OrderByCast;
use App\Data\Casts\Paginate\PaginateCast;
use App\Data\Casts\SearchBy\SearchByCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use App\Queries\Shared\Result\ResultInterface;
use Spatie\LaravelData\Attributes\MapInputName;
use App\Queries\Shared\SearchBy\SearchByInterface;
use App\Data\Casts\With\WithCast as WithRelationCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;

#[Handler(\App\Queries\Role\GetByFilter\GetByFilterHandler::class)]
final class GetByFilterQuery extends Query implements ObjectDefaultsInterface
{
    public function __construct(
        #[WithCast(SelectCast::class)]
        public readonly ?array $select = null,
        public readonly Role $role = new Role(),
        #[MapInputName('search')]
        #[WithCast(SearchByCast::class, Role::class)]
        public readonly ?SearchByInterface $searchBy = null,
        public readonly ?array $ignore = null,
        #[WithCast(WithRelationCast::class)]
        public readonly ?array $with = null,
        #[MapInputName('orderby')]
        #[WithCast(OrderByCast::class)]
        public readonly ?OrderBy $orderBy = null,
        #[MapInputName('paginate')]
        #[WithCast(PaginateCast::class)]
        public readonly ?ResultInterface $result = null
    ) {
    }
}
