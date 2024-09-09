<?php

declare(strict_types=1);

namespace App\CacheQueries;

interface CacheQueryBusInterface
{
    public function execute(CacheQuery $query): mixed;
}
