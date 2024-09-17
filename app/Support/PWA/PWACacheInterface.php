<?php

declare(strict_types=1);

namespace App\Support\PWA;

interface PWACacheInterface
{
    public static function getUrlForPWA(): string;
}
