<?php

namespace App\Http\Controllers\Api\PWA\Files;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Actions\PWA\GetAssets\GetAssetsAction;
use App\Actions\PWA\GetAssets\GetAssetsHandler;

class FilesController extends Controller
{
    /**
     * Get all assets required for the PWA.
     */
    public function __invoke(GetAssetsHandler $handler): JsonResponse
    {
        return Response::json(['data' => $handler->handle(new GetAssetsAction())]);
    }
}
