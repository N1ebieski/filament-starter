<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\SplitExacts;

use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\HandlerInterface;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\Splits;
use Closure;

final class SplitExactsHandler implements HandlerInterface
{
    public function handle(Splits $splits, Closure $next): Splits
    {
        preg_match_all('/"(.*?)"/', $splits->term, $matches);

        foreach ($matches[0] as $match) {
            $exacts[] = '+'.$match;

            $splits->term = trim(str_replace($match, '', $splits->term));
        }

        $splits->exacts = ! empty($exacts) ? $exacts : null;

        return $next($splits);
    }
}
