<?php

declare(strict_types=1);

namespace App\Support\Query;

use App\Queries\QueryBusInterface;
use Illuminate\Support\Facades\App;

trait HasQueryBus
{
    protected static function getQueryBus(): QueryBusInterface
    {
        return App::make(QueryBusInterface::class);
    }
}
