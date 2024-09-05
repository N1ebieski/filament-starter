<?php

declare(strict_types=1);

namespace App\Scopes\Search;

use App\Support\Enum\Enum;
use App\Support\Enum\EnumInterface;

enum Driver: string implements EnumInterface
{
    use Enum;

    case Scout = 'scout';

    case DatabaseMatch = 'database-match';
}
