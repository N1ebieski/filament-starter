<?php

namespace Tests\Feature\Listeners\Queue\Retry;

use Tests\TestCase;
use DG\BypassFinals;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RetryListenerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        BypassFinals::enable(bypassReadOnly: false);

        parent::setUp();

        Config::set('queue.default', 'database');
    }


    /**
     * Test that a job that implements RetryInterface, can make use of the retried method.
     *
     * @return void
     */
    public function test_job_with_retried(): void
    {
        $job = new ExampleWithRetriedJob();

        $this->assertFalse(Cache::driver('testing')->get('retried'));

        $job->dispatch();

        Artisan::call('queue:work --once');

        $failedJob = DB::table('failed_jobs')->first();

        Artisan::call("queue:retry {$failedJob->uuid}");

        $this->assertTrue(Cache::driver('testing')->get('retried'));
    }
}
