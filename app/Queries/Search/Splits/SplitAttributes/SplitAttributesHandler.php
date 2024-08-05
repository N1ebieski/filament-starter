<?php

declare(strict_types=1);

namespace App\Queries\Search\Splits\SplitAttributes;

use Closure;
use App\Queries\Search\Splits\Splits;
use App\Queries\Search\Splits\HandlerInterface;

final class SplitAttributesHandler implements HandlerInterface
{
    public function handle(Splits $splits, Closure $next): Splits
    {
        if ($splits->model) {
            $searchableColumns = implode('|', $splits->model->searchableAttributes ?? []);

            preg_match_all('/attr:(' . $searchableColumns . '):\"(.*?)\"/', $splits->term, $matches);

            $attributes = [];

            foreach ($matches[0] as $key => $value) {
                $attributes[trim($matches[1][$key])] = trim(str_replace('"', '', $matches[2][$key]));

                $splits->term = trim(str_replace($value, '', $splits->term));
            }

            $splits->attributes = !empty($attributes) ? $attributes : null;
        }

        return $next($splits);
    }
}
