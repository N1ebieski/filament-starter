<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\SplitRelations;

use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\HandlerInterface;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\HasSymbol;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\Splits;
use Closure;
use Illuminate\Database\Eloquent\Model;

final class SplitRelationsHandler implements HandlerInterface
{
    use HasSymbol;

    public function handle(Splits $splits, Closure $next): Splits
    {
        if ($splits->model instanceof Model) {
            $searchableRelations = implode('|', $splits->model->searchableRelations ?? []);

            preg_match_all('/rel:('.$searchableRelations.'):\"(.*?)\"/', $splits->term, $matches);

            $relations = [];

            foreach ($matches[0] as $key => $value) {
                $looseMatches = explode(' ', trim($matches[2][$key]));

                $looses = [];

                foreach ($looseMatches as $match) {
                    if (strlen($match) >= 3) {
                        $match = $this->isContainsSymbol($match) ?
                            '"'.str_replace('"', '', $match).'"' : $match;

                        if ($match === end($looseMatches)) {
                            $match .= '*';
                        }

                        $looses[] = '+'.$match;
                    }
                }

                $relations[trim($matches[1][$key])] = $looses;

                $splits->term = trim(str_replace($value, '', $splits->term));
            }

            $splits->relations = $relations === [] ? null : $relations;
        }

        return $next($splits);
    }
}
