<?php

declare(strict_types=1);

namespace App\Support\Queue;

interface RetryInterface
{
    public function retried(): void;
}
