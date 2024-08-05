<?php

declare(strict_types=1);

namespace App\Queries\Search;

use App\Queries\Search\Search;
use Illuminate\Support\Facades\App;
use App\Queries\Search\Splits\Splits;
use Illuminate\Database\Eloquent\Model;
use App\Queries\Search\Splits\HandlerInterface;
use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline;
use App\Overrides\Illuminate\Contracts\Container\Container;
use App\Queries\Search\Splits\SplitExacts\SplitExactsHandler;
use App\Queries\Search\Splits\SplitLosses\SplitLossesHandler;
use App\Queries\Search\Splits\SplitRelations\SplitRelationsHandler;
use App\Queries\Search\Splits\SplitAttributes\SplitAttributesHandler;

final class SearchFactory
{
    public static function makeSearch(string $term, ?Model $model = null): Search
    {
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

        return Search::from($splits);
    }
}
