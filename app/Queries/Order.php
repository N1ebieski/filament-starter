<?php

declare(strict_types=1);

namespace App\Queries;

enum Order: string
{
    case Asc = 'asc';

    case Desc = 'desc';
}
