<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Queue\Retry;

use Illuminate\Bus\Queueable;
use App\Support\Queue\RetryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

final class ExampleWithRetriedJob implements ShouldQueue, RetryInterface
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
