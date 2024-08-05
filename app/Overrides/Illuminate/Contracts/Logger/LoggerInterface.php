<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Logger;

interface LoggerInterface
{
    /**
     * Get a log channel instance.
     *
     * @param  string|null  $channel
     * @return \Psr\Log\LoggerInterface
     */
    public function channel($channel = null);
}
