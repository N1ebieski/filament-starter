<?php

declare(strict_types=1);

namespace App\Queries\User\GetByFilter;

use App\Queries\Get;
use App\Queries\Query;
use App\Queries\Search;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Models\User\User;
use App\Queries\Paginate;
use App\Models\Tenant\Tenant;
use App\Data\Casts\Search\SearchCast;
use App\Data\ObjectDefaultsInterface;
use Spatie\LaravelData\Casts\EnumCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use Illuminate\Database\Eloquent\Collection;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use App\Data\Casts\ModelCollectionOf\ModelCollectionOfCast;

#[Handler(\App\Queries\User\GetByFilter\GetByFilterHandler::class)]
final class GetByFilterQuery extends Query implements ObjectDefaultsInterface
{
    public function __construct(
        #[WithCast(EnumCast::class)]
        public readonly ?StatusEmail $status_email = null,
        #[WithCast(ModelCollectionOfCast::class, Role::class)]
        public readonly Collection $roles = new Collection(),
        #[WithCast(ModelCollectionOfCast::class, Tenant::class)]
        public readonly Collection $tenants = new Collection(),
        #[WithCast(SearchCast::class, User::class)]
        public readonly ?Search $search = null,
        public readonly ?array $except = null,
        public readonly User $user = new User(),
        public readonly ?OrderBy $orderby = null,
        public readonly Paginate|Get|null $result = null
    ) {
    }
}
