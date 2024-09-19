<?php

declare(strict_types=1);

namespace App\Scopes;

/**
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator filterPaginate(\App\Queries\Shared\Result\Drivers\Paginate\Paginate $paginate)
 * @method \Illuminate\Database\Eloquent\Collection filterGet(\App\Queries\Shared\Result\Drivers\Get\Get $get)
 */
interface FiltersScopesInterface {}
