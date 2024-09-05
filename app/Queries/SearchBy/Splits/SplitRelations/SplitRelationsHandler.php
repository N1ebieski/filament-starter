<?php

declare(strict_types=1);

namespace App\Queries\SearchBy\Splits\SplitRelations;

use Closure;
use App\Queries\SearchBy\Splits\Splits;
use App\Queries\SearchBy\Splits\HasSymbol;
use App\Queries\SearchBy\Splits\HandlerInterface;

final class SplitRelationsHandler implements HandlerInterface
{
    use HasSymbol;

    public function handle(Splits $splits, Closure $next): Splits
    {
        if ($splits->model) {
            $searchableRelations = implode('|', $splits->model->searchableRelations ?? []);

            preg_match_all('/rel:(' . $searchableRelations . '):\"(.*?)\"/', $splits->term, $matches);

            $relations = [];

            foreach ($matches[0] as $key => $value) {
                $looseMatches = explode(' ', trim($matches[2][$key]));

                $looses = [];

                foreach ($looseMatches as $match) {
                    if (strlen($match) >= 3) {
                        $match = $this->isContainsSymbol($match) ?
                            '"' . str_replace('"', '', $match) . '"' : $match;

                        if ($match === end($looseMatches)) {
                            $match .= '*';
                        }

                        $looses[] = '+' . $match;
                    }
                }

                $relations[trim($matches[1][$key])] = $looses;

                $splits->term = trim(str_replace($value, '', $splits->term));
            }

            $splits->relations = !empty($relations) ? $relations : null;
        }

        return $next($splits);
    }
}
