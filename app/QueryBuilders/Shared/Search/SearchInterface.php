<?php

declare(strict_types=1);

namespace App\QueryBuilders\Shared\Search;

/**
 * @method \Illuminate\Database\Eloquent\Builder&SearchInterface filterSearchBy(?\App\Queries\Shared\SearchBy\SearchByInterface $searchBy)
 * @method \Illuminate\Database\Eloquent\Builder&SearchInterface filterOrderByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder&SearchInterface filterSearchByScout(\App\Queries\Shared\SearchBy\Drivers\Scout\Scout $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder&SearchInterface filterSearchByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch, string $boolean = 'and')
 * @method \Illuminate\Database\Eloquent\Builder&SearchInterface filterSearchAttributesByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder&SearchInterface filterOrderByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 */
interface SearchInterface {}
