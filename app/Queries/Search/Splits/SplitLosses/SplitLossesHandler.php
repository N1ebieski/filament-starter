<?php

declare(strict_types=1);

namespace App\Queries\Search\Splits\SplitLosses;

use Closure;
use App\Queries\Search\Splits\Splits;
use App\Queries\Search\Splits\HasSymbol;
use App\Queries\Search\Splits\HandlerInterface;

final class SplitLossesHandler implements HandlerInterface
{
    use HasSymbol;

    public function handle(Splits $splits, Closure $next): Splits
    {
        $matches = explode(' ', $splits->term);

        foreach ($matches as $match) {
            if (strlen($match) >= 3) {
                $value = $this->isContainsSymbol($match) ?
                    '"' . str_replace('"', '', $match) . '"' : $match;

                if ($value === end($matches)) {
                    $value .= '*';
                }

                $looses[] = '+' . $value;

                $splits->term = trim(str_replace($match, '', $splits->term));
            }
        }

        $splits->looses = !empty($looses) ? $looses : null;

        return $next($splits);
    }
}
