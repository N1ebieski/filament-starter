<?php

declare(strict_types=1);

namespace App\ValueObjects\Role\Name;

enum DefaultName: string
{
    case User = 'user';

    case Admin = 'admin';

    case SuperAdmin = 'super-admin';

    case Api = 'api';
}
