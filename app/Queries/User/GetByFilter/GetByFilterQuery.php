<?php

declare(strict_types=1);

namespace App\Queries\User\GetByFilter;

use App\Queries\Query;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Models\User\User;
use App\Queries\Result\Get;
use App\Models\Tenant\Tenant;
use App\Queries\Result\Paginate;
use Spatie\LaravelData\Casts\EnumCast;
use App\Data\Casts\OrderBy\OrderByCast;
use App\Queries\Result\ResultInterface;
use App\Data\Casts\Paginate\PaginateCast;
use App\Data\Casts\SearchBy\SearchByCast;
use App\Queries\SearchBy\SearchByInterface;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use Illuminate\Database\Eloquent\Collection;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;

#[Handler(\App\Queries\User\GetByFilter\GetByFilterHandler::class)]
final class GetByFilterQuery extends Query implements ObjectDefaultsInterface
{
    public function __construct(
        #[WithCast(EnumCast::class)]
        public readonly ?StatusEmail $status_email = null,
        #[WithCast(CollectionOfModelsCast::class, Role::class)]
        public readonly Collection $roles = new Collection(),
        #[WithCast(CollectionOfModelsCast::class, Tenant::class)]
        public readonly Collection $tenants = new Collection(),
        #[WithCast(SearchByCast::class, User::class)]
        public readonly ?SearchByInterface $searchBy = null,
        public readonly ?array $except = null,
        public readonly User $user = new User(),
        #[WithCast(OrderByCast::class)]
        public readonly ?OrderBy $orderBy = null,
        #[WithCast(PaginateCast::class)]
        public readonly ?ResultInterface $result = null
    ) {
    }
}
