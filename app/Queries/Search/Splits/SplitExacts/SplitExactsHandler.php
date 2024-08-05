<?php

declare(strict_types=1);

namespace App\Queries\Search\Splits\SplitExacts;

use Closure;
use App\Queries\Search\Splits\Splits;
use App\Queries\Search\Splits\HasSymbol;
use App\Queries\Search\Splits\HandlerInterface;

final class SplitExactsHandler implements HandlerInterface
{
    public function handle(Splits $splits, Closure $next): Splits
    {
        preg_match_all('/"(.*?)"/', $splits->term, $matches);

        foreach ($matches[0] as $match) {
            $exacts[] = '+' . $match;

            $splits->term = trim(str_replace($match, '', $splits->term));
        }

        $splits->exacts = !empty($exacts) ? $exacts : null;

        return $next($splits);
    }
}
