<?php

declare(strict_types=1);

namespace App\Listeners\Queue\Retry;

use RuntimeException;
use App\Support\Queue\RetryInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Queue\Events\JobRetryRequested;

final class RetryListener
{
    public function __construct(private readonly Container $container)
    {
    }

    public function handle(JobRetryRequested $event): void
    {
        $payload = $event->payload();

        $job = $this->getCommand($payload['data']);

        if ($job instanceof RetryInterface) {
            $job->retried();
        }
    }

    /**
     * Get the command from the given payload.
     *
     * @param  array  $data
     * @return mixed
     * @see \Illuminate\Queue\CallQueuedHandler::getCommand
     *
     * @throws \RuntimeException
     */
    private function getCommand(array $data)
    {
        if (str_starts_with($data['command'], 'O:')) {
            return unserialize($data['command']);
        }

        if ($this->container->bound(Encrypter::class)) {
            return unserialize($this->container[Encrypter::class]->decrypt($data['command']));
        }

        throw new RuntimeException('Unable to extract job payload.');
    }
}
