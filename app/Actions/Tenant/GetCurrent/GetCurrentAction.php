<?php

declare(strict_types=1);

namespace App\Actions\Tenant\GetCurrent;

use App\Actions\Action;
use App\Support\Attributes\Handler\Handler;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[Handler(\App\Actions\Tenant\GetCurrent\GetCurrentHandler::class)]
#[MapName(SnakeCaseMapper::class)]
final class GetCurrentAction extends Action {}
