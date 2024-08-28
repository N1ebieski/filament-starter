<?php

declare(strict_types=1);

namespace App\Services\Queue;

use Illuminate\Contracts\Encryption\Encrypter;

final class JobFactory
{
    public function __construct(private readonly Encrypter $encrypter)
    {
    }

    /**
     * Unserialize a command, decrypting it if necessary and returns the corresponding job.
     *
     * @param string $command The command data to be processed.
     *
     * @return mixed The job corresponding to the command data.
     */
    public function makeJobFromCommand(string $command): mixed
    {
        if (str_starts_with($command, 'O:')) {
            return unserialize($command);
        }

        return unserialize($this->encrypter->decrypt($command));
    }
}
