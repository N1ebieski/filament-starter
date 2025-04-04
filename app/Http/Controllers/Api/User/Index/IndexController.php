<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Index;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Index\IndexRequest;
use App\Http\Resources\User\UserResource;
use App\Queries\QueryBusInterface;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Spatie\LaravelData\PaginatedDataCollection;

final class IndexController extends Controller
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function __invoke(
        IndexRequest $request,
    ): JsonResponse {
        $data = $request->toArray();

        /** @var LengthAwarePaginator $users */
        $users = $this->queryBus->execute(GetByFilterQuery::from($data));

        /** @var PaginatedDataCollection $collection */
        $collection = UserResource::collect($users, PaginatedDataCollection::class);

        return Response::json($collection->toArray());
    }
}
