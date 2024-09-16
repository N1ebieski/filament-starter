<?php

declare(strict_types=1);

namespace App\Actions\PWA\GetAssets;

use App\Actions\Action;
use App\Support\Attributes\Handler\Handler;

#[Handler(\App\Actions\PWA\GetAssets\GetAssetsHandler::class)]
final class GetAssetsAction extends Action
{
}
