<?php

declare(strict_types=1);

namespace Tests\Unit\Overrides\Illuminate;

use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline as PipelineContract;
use App\Overrides\Illuminate\Pipeline\Pipeline;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Pipeline\Pipeline as BasePipeline;

final class PipelineFactory
{
    public static function makePipeline(ContainerContract $container = new Container): PipelineContract
    {
        return new Pipeline(new BasePipeline($container));
    }
}
