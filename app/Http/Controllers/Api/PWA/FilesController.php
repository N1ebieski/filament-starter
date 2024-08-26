<?php

namespace App\Http\Controllers\Api\PWA;

use App\Services\PWA\PWAService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class FilesController extends Controller
{
    public function __invoke(PWAService $pwaService): JsonResponse
    {
        return Response::json(['data' => $pwaService->getAssets()]);
    }
}
