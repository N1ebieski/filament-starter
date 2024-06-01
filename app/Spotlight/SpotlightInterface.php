<?php

declare(strict_types=1);

namespace App\Spotlight;

interface SpotlightInterface
{
    public static function shouldRegisterSpotlight(): bool;
}
