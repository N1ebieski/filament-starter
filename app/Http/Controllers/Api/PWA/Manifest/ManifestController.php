<?php

namespace App\Http\Controllers\Api\PWA\Manifest;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Overrides\LaravelPWA\Services\ManifestService;

class ManifestController extends Controller
{
    public function __invoke(ManifestService $service): JsonResponse
    {
        return Response::json($service->generate());
    }
}
