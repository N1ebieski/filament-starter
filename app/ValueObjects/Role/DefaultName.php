<?php

declare(strict_types=1);

namespace App\ValueObjects\Role;

enum DefaultName: string
{
    case USER = 'user';

    case ADMIN = 'admin';

    case SUPER_ADMIN = 'super-admin';

    case API = 'api';
}
