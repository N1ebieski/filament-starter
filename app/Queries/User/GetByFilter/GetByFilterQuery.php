<?php

declare(strict_types=1);

namespace App\Queries\User\GetByFilter;

use App\Queries\Query;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Models\User\User;
use App\Models\Tenant\Tenant;
use Spatie\LaravelData\Casts\EnumCast;
use App\Data\Casts\OrderBy\OrderByCast;
use App\Data\Casts\Paginate\PaginateCast;
use App\Data\Casts\SearchBy\SearchByCast;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\WithCast;
use Illuminate\Database\Eloquent\Collection;
use App\Queries\Shared\Result\ResultInterface;
use Spatie\LaravelData\Attributes\MapInputName;
use App\Queries\Shared\SearchBy\SearchByInterface;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use App\Data\Casts\CollectionOfModels\CollectionOfModelsCast;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;

#[Handler(\App\Queries\User\GetByFilter\GetByFilterHandler::class)]
final class GetByFilterQuery extends Query implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly array|string|null $selects = null,
        #[WithCast(EnumCast::class)]
        public readonly ?StatusEmail $status_email = null,
        #[WithCast(CollectionOfModelsCast::class, Role::class)]
        public readonly Collection $roles = new Collection(),
        #[WithCast(CollectionOfModelsCast::class, Tenant::class)]
        public readonly Collection $tenants = new Collection(),
        #[WithCast(SearchByCast::class, User::class)]
        public readonly ?SearchByInterface $searchBy = null,
        public readonly ?array $ignores = null,
        #[MapInputName('include')]
        public readonly ?array $includes = null,
        public readonly User $user = new User(),
        #[WithCast(OrderByCast::class)]
        public readonly ?OrderBy $orderBy = null,
        #[WithCast(PaginateCast::class)]
        public readonly ?ResultInterface $result = null
    ) {
    }
}
