<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers;

use App\Support\Handler\HandlerHelper;
use App\Queries\Shared\SearchBy\Drivers\Handler;
use App\Queries\Shared\SearchBy\SearchByInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

final class DriverHandlerFactory
{
    public static function makeHandler(SearchByInterface $searchBy, Builder $builder): Handler
    {
        $handlerName = HandlerHelper::getNamespace($searchBy);

        /** @var Handler */
        $handler = new $handlerName($builder);

        return $handler;
    }
}
