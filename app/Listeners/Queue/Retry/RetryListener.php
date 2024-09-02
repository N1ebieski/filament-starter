<?php

declare(strict_types=1);

namespace App\Listeners\Queue\Retry;

use App\Listeners\Queue\JobFactory;
use App\Support\Queue\RetryInterface;
use Illuminate\Queue\Events\JobRetryRequested;

final class RetryListener
{
    public function __construct(private readonly JobFactory $jobFactory)
    {
    }

    /**
     * Handle the event when a job retry is requested.
     *
     * @param \Illuminate\Queue\Events\JobRetryRequested $event
     * @return void
     */
    public function handle(JobRetryRequested $event): void
    {
        $payload = $event->payload();

        if (!isset($payload['data']) || !is_array($payload['data'])) {
            return;
        }

        if (!isset($payload['data']['command']) || !is_string($payload['data']['command'])) {
            return;
        }

        $job = $this->jobFactory->makeJob($payload['data']['command']);

        if ($job instanceof RetryInterface) {
            $job->retried();
        }
    }
}
