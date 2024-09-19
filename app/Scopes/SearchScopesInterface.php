<?php

declare(strict_types=1);

namespace App\Scopes;

/**
 * @method \Illuminate\Database\Eloquent\Builder&SearchScopesInterface filterSearchBy(?\App\Queries\Shared\SearchBy\SearchByInterface $searchBy)
 * @method \Illuminate\Database\Eloquent\Builder&SearchScopesInterface filterOrderByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder&SearchScopesInterface filterSearchByScout(\App\Queries\Shared\SearchBy\Drivers\Scout\Scout $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder&SearchScopesInterface filterSearchByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch, string $boolean = 'and')
 * @method \Illuminate\Database\Eloquent\Builder&SearchScopesInterface filterSearchAttributesByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method \Illuminate\Database\Eloquent\Builder&SearchScopesInterface filterOrderByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 */
interface SearchScopesInterface {}
