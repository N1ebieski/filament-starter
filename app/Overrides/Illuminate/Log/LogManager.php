<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Log;

use App\Overrides\Illuminate\Contracts\Logger\LoggerInterface;
use Illuminate\Log\LogManager as BaseLogManager;

final class LogManager implements LoggerInterface
{
    public function __construct(private readonly BaseLogManager $logManager) {}

    /**
     * Laravel doesn't define channel method in Contract.
     *
     * Get a log channel instance.
     *
     * @param  string|null  $channel
     * @return \Psr\Log\LoggerInterface
     */
    public function channel($channel = null)
    {
        return $this->logManager->channel($channel);
    }
}
