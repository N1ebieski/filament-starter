<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers;

use App\Support\Handler\HandlerHelper;
use App\Queries\Shared\Result\ResultInterface;
use App\Queries\Shared\SearchBy\Drivers\Handler;
use Illuminate\Contracts\Database\Eloquent\Builder;

final class DriverHandlerFactory
{
    public static function makeHandler(ResultInterface $result, Builder $builder): Handler
    {
        $handlerName = HandlerHelper::getNamespace($result);

        return new $handlerName($builder);
    }
}
