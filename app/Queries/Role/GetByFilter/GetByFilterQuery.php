<?php

declare(strict_types=1);

namespace App\Queries\Role\GetByFilter;

use App\Queries\Get;
use App\Queries\Query;
use App\Queries\Search;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Queries\Paginate;
use App\Data\Casts\Search\SearchCast;
use App\Data\ObjectDefaultsInterface;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;

#[Handler(\App\Queries\Role\GetByFilter\GetByFilterHandler::class)]
final class GetByFilterQuery extends Query implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Role $role = new Role(),
        #[WithCast(SearchCast::class, Role::class)]
        public readonly ?Search $search = null,
        public readonly ?array $except = null,
        public readonly ?OrderBy $orderby = null,
        public readonly Paginate|Get|null $result = null
    ) {
    }
}
