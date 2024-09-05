<?php

declare(strict_types=1);

namespace App\Queries\SearchBy\Drivers\DatabaseMatch;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline;
use App\Overrides\Illuminate\Contracts\Container\Container;
use App\Queries\SearchBy\Drivers\DatabaseMatch\DatabaseMatch;
use App\Queries\SearchBy\Drivers\DatabaseMatch\Splits\Splits;
use App\Queries\SearchBy\Drivers\DatabaseMatch\Splits\HandlerInterface;
use App\Queries\SearchBy\Drivers\DatabaseMatch\Splits\SplitExacts\SplitExactsHandler;
use App\Queries\SearchBy\Drivers\DatabaseMatch\Splits\SplitLosses\SplitLossesHandler;
use App\Queries\SearchBy\Drivers\DatabaseMatch\Splits\SplitRelations\SplitRelationsHandler;
use App\Queries\SearchBy\Drivers\DatabaseMatch\Splits\SplitAttributes\SplitAttributesHandler;

final class DatabaseMatchFactory
{
    public static function makeDatabaseMatch(
        string $term,
        bool $isOrderBy = true,
        ?Model $model = null
    ): DatabaseMatch {
        /** @var Container */
        $container = App::make(Container::class);

        /** @var Pipeline */
        $pipeline = App::make(Pipeline::class);

        /** @var array<HandlerInterface> */
        $handlers = $container->makeMany([
            SplitAttributesHandler::class,
            SplitRelationsHandler::class,
            SplitExactsHandler::class,
            SplitLossesHandler::class
        ]);

        /** @var Splits */
        $splits = $pipeline->through(...$handlers)->process(new Splits(
            model: $model,
            term: $term
        ));

        return DatabaseMatch::from([
            ...$splits->toArray(),
            'isOrderBy' => $isOrderBy
        ]);
    }
}
