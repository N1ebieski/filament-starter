<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers\Get;

use Illuminate\Database\Eloquent\Collection;
use App\Queries\Shared\Result\Drivers\Handler;

final class GetHandler extends Handler
{
    public function handle(Get $get): Collection
    {
        return $this->builder->filterGet($get);
    }
}
