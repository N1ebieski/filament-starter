<?php

declare(strict_types=1);

namespace App\Queries\User\GetByFilter;

use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Casts\OrderBy\OrderByCast;
use App\Data\Casts\Paginate\PaginateCast;
use App\Data\Casts\SearchBy\SearchByCast;
use App\Data\Casts\Select\SelectCast;
use App\Data\Casts\With\WithCast as WithRelationCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Models\Role\Role;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Queries\OrderBy;
use App\Queries\Query;
use App\Queries\Shared\Result\ResultInterface;
use App\Queries\Shared\SearchBy\SearchByInterface;
use App\Support\Attributes\Handler\Handler;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

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
        #[MapInputName('search')]
        #[WithCast(SearchByCast::class, User::class)]
        public readonly ?SearchByInterface $searchBy = null,
        public readonly ?array $ignore = null,
        #[WithCast(WithRelationCast::class)]
        public readonly ?array $with = null,
        public readonly User $user = new User,
        #[MapInputName('orderby')]
        #[WithCast(OrderByCast::class)]
        public readonly ?OrderBy $orderBy = null,
        #[MapInputName('paginate')]
        #[WithCast(PaginateCast::class)]
        public readonly ?ResultInterface $result = null
    ) {}
}
