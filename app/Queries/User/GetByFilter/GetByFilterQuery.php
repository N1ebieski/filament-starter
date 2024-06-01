<?php

declare(strict_types=1);

namespace App\Queries\User\GetByFilter;

use App\Queries\Get;
use App\Queries\Query;
use App\Queries\Search;
use App\Queries\OrderBy;
use App\Models\User\User;
use App\Queries\Paginate;
use App\Support\Attributes\Handler;
use App\ValueObjects\User\StatusEmail;
use Illuminate\Database\Eloquent\Collection;

#[Handler(\App\Queries\User\GetByFilter\GetByFilterHandler::class)]
final class GetByFilterQuery extends Query
{
    public function __construct(
        public readonly ?StatusEmail $status_email = null,
        public readonly Collection $roles = new Collection(),
        public readonly Collection $tenants = new Collection(),
        public readonly ?Search $search = null,
        public readonly ?array $except = null,
        public readonly User $user = new User(),
        public readonly ?OrderBy $orderby = null,
        public readonly Paginate|Get|null $result = null
    ) {
    }
}
