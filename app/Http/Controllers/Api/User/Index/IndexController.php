<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Index;

use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use App\Queries\QueryBusInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\User\UserResource;
use Spatie\LaravelData\PaginatedDataCollection;
use App\Http\Requests\Api\User\Index\IndexRequest;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class IndexController extends Controller
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(
        IndexRequest $request,
    ): JsonResponse {
        Gate::authorize('viewAny', User::class);

        $data = $request->toArray();

        /** @var LengthAwarePaginator */
        $users = $this->queryBus->execute(GetByFilterQuery::from($data));

        /** @var PaginatedDataCollection */
        $collection = UserResource::collect($users, PaginatedDataCollection::class);

        return Response::json($collection->toArray());
    }
}
