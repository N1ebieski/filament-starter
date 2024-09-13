<?php

declare(strict_types=1);

namespace App\Queries\User\GetByFilter;

use App\Queries\Query;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use Illuminate\Support\Collection;
use App\Data\Casts\Select\SelectCast;
use Spatie\LaravelData\Casts\EnumCast;
use App\Data\Casts\OrderBy\OrderByCast;
use App\Data\Casts\Paginate\PaginateCast;
use App\Data\Casts\SearchBy\SearchByCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use App\Queries\Shared\Result\ResultInterface;
use App\Queries\Shared\SearchBy\SearchByInterface;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use App\Data\Casts\With\WithCast as WithRelationCast;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;

#[Handler(\App\Queries\User\GetByFilter\GetByFilterHandler::class)]
final class GetByFilterQuery extends Query implements ObjectDefaultsInterface
{
    public function __construct(
        #[WithCast(SelectCast::class)]
        public readonly ?array $select = null,
        #[WithCast(EnumCast::class)]
        public readonly ?StatusEmail $status_email = null,
        #[WithCast(CollectionOfModelsCast::class, Role::class)]
        public readonly ?Collection $roles = null,
        #[WithCast(CollectionOfModelsCast::class, Tenant::class)]
        public readonly ?Collection $tenants = null,
        #[WithCast(SearchByCast::class, User::class)]
        public readonly ?SearchByInterface $searchBy = null,
        public readonly ?array $ignore = null,
        #[WithCast(WithRelationCast::class)]
        public readonly ?array $with = null,
        public readonly User $user = new User(),
        #[WithCast(OrderByCast::class)]
        public readonly ?OrderBy $orderBy = null,
        #[WithCast(PaginateCast::class)]
        public readonly ?ResultInterface $result = null
    ) {
    }
}
