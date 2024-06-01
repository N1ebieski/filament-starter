<?php

declare(strict_types=1);

namespace App\Queries;

interface QueryBusInterface
{
    public function execute(Query $query): mixed;
}
