<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\SplitLosses;

use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\HandlerInterface;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\HasSymbol;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\Splits;
use Closure;

final class SplitLossesHandler implements HandlerInterface
{
    use HasSymbol;

    public function handle(Splits $splits, Closure $next): Splits
    {
        $matches = explode(' ', $splits->term);

        $looses = [];

        foreach ($matches as $match) {
            if (strlen($match) >= 3) {
                $value = $this->isContainsSymbol($match) ?
                    '"'.str_replace('"', '', $match).'"' : $match;

                if ($value === end($matches)) {
                    $value .= '*';
                }

                $looses[] = '+'.$value;

                $splits->term = trim(str_replace($match, '', $splits->term));
            }
        }

        $splits->looses = ! empty($looses) ? $looses : null;

        return $next($splits);
    }
}
