<?php

namespace App\Http\Controllers\Api\PWA\Files;

use App\Actions\PWA\GetAssets\GetAssetsAction;
use App\CacheQueries\CacheQueryBusInterface;
use App\CacheQueries\PWA\GetAssets\GetAssetsCacheQuery;
use App\CacheQueries\Time;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class FilesController extends Controller
{
    /**
     * Get all assets required for the PWA.
     */
    public function __invoke(CacheQueryBusInterface $cacheQuery): JsonResponse
    {
        /** @var array<int, string> */
        $assets = $cacheQuery->execute(new GetAssetsCacheQuery(
            action: new GetAssetsAction,
            time: new Time(Config::get('cache.minutes'))
        ));

        return Response::json(['data' => $assets]);
    }
}
