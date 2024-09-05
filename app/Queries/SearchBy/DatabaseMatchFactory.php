<?php

declare(strict_types=1);

namespace App\Queries\SearchBy;

use Illuminate\Support\Facades\App;
use App\Queries\SearchBy\DatabaseMatch;
use App\Queries\SearchBy\Splits\Splits;
use Illuminate\Database\Eloquent\Model;
use App\Queries\SearchBy\Splits\HandlerInterface;
use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline;
use App\Overrides\Illuminate\Contracts\Container\Container;
use App\Queries\SearchBy\Splits\SplitExacts\SplitExactsHandler;
use App\Queries\SearchBy\Splits\SplitLosses\SplitLossesHandler;
use App\Queries\SearchBy\Splits\SplitRelations\SplitRelationsHandler;
use App\Queries\SearchBy\Splits\SplitAttributes\SplitAttributesHandler;

final class DatabaseMatchFactory
{
    public static function makeDatabaseMatch(
        string $term,
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

        return DatabaseMatch::from($splits);
    }
}
