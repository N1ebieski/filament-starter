<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Queue\Retry;

use App\Support\Queue\RetryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

final class ExampleWithRetriedJob implements RetryInterface, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        Cache::driver('testing')->set('retried', false);
    }

    public function retried(): void
    {
        Cache::driver('testing')->set('retried', true);
    }

    public function handle(): void
    {
        $this->fail();
    }
}
