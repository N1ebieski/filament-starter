<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers\Paginate;

use App\Queries\Shared\Result\Drivers\Handler;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class PaginateHandler extends Handler
{
    public function handle(Paginate $paginate): LengthAwarePaginator
    {
        return $this->builder->filterPaginate($paginate);
    }
}
