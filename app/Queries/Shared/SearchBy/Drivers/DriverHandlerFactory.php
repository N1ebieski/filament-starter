<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers;

use App\Queries\Shared\SearchBy\SearchByInterface;
use App\Support\Handler\HandlerHelper;
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
