<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Queries\Search;
use App\Queries\SearchFactory;
use Illuminate\Support\Facades\App;

trait HasGlobalSearch
{
    use HasQueryBus;

    protected static function getSearchFactory(): SearchFactory
    {
        return App::make(SearchFactory::class);
    }

    protected static function getSearch(?string $search): ?Search
    {
        return !is_null($search) && mb_strlen($search) > 2 ?
            static::getSearchFactory()->getSearch($search, App::make(static::getModel())) : null;
    }

    abstract public static function getModel(): string;
}
