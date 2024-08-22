<?php

declare(strict_types=1);

namespace App\CacheQueries\Shared;

use App\Exceptions\Exception;

final class NullResultException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Result of Query cannot be null';
}
