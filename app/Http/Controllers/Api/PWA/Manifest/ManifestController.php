<?php

namespace App\Http\Controllers\Api\PWA\Manifest;

use App\Http\Controllers\Controller;
use App\Overrides\LaravelPWA\Services\ManifestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ManifestController extends Controller
{
    public function __invoke(ManifestService $service): JsonResponse
    {
        return Response::json($service->generate());
    }
}
