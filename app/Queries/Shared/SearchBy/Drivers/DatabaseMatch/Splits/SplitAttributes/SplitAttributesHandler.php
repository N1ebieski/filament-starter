<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\SplitAttributes;

use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\HandlerInterface;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits\Splits;
use Closure;
use Illuminate\Database\Eloquent\Model;

final class SplitAttributesHandler implements HandlerInterface
{
    public function handle(Splits $splits, Closure $next): Splits
    {
        if ($splits->model instanceof Model) {
            $searchableColumns = implode('|', $splits->model->searchableAttributes ?? []);

            preg_match_all('/attr:('.$searchableColumns.'):\"(.*?)\"/', $splits->term, $matches);

            $attributes = [];

            foreach ($matches[0] as $key => $value) {
                $attributes[trim($matches[1][$key])] = trim(str_replace('"', '', $matches[2][$key]));

                $splits->term = trim(str_replace($value, '', $splits->term));
            }

            $splits->attributes = $attributes === [] ? null : $attributes;
        }

        return $next($splits);
    }
}
