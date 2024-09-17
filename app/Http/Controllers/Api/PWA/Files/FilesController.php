<?php

namespace App\Http\Controllers\Api\PWA\Files;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\CacheQueries\CacheQueryBusInterface;
use App\Actions\PWA\GetAssets\GetAssetsAction;
use App\CacheQueries\PWA\GetAssets\GetAssetsCacheQuery;

class FilesController extends Controller
{
    /**
     * Get all assets required for the PWA.
     */
    public function __invoke(CacheQueryBusInterface $cacheQuery): JsonResponse
    {
        /** @var array<int, string> */
        $assets = $cacheQuery->execute(new GetAssetsCacheQuery(
            action: new GetAssetsAction(),
        ));

        return Response::json(['data' => $assets]);
    }
}
