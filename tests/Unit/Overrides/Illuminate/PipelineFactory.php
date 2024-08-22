<?php

declare(strict_types=1);

namespace Tests\Unit\Overrides\Illuminate;

use Illuminate\Container\Container;
use App\Overrides\Illuminate\Pipeline\Pipeline;
use Illuminate\Pipeline\Pipeline as BasePipeline;
use Illuminate\Contracts\Container\Container as ContainerContract;
use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline as PipelineContract;

final class PipelineFactory
{
    public static function makePipeline(ContainerContract $container = new Container()): PipelineContract
    {
        return new Pipeline(new BasePipeline($container));
    }
}
