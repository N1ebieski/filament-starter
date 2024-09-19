<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers;

use App\Queries\Shared\Result\ResultInterface;
use App\Support\Handler\HandlerHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;

final class DriverHandlerFactory
{
    public static function makeHandler(ResultInterface $result, Builder $builder): Handler
    {
        $handlerName = HandlerHelper::getNamespace($result);

        /** @var Handler */
        $handler = new $handlerName($builder);

        return $handler;
    }
}
