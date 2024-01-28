<?php

declare(strict_types=1);

namespace App\Queries;

enum Order: string
{
    case ASC = 'asc';

    case DESC = 'desc';
}
