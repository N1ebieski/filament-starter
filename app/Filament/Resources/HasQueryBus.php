<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Queries\QueryBus;
use Illuminate\Support\Facades\App;

trait HasQueryBus
{
    protected static function getQueryBus(): QueryBus
    {
        return App::make(QueryBus::class);
    }
}
