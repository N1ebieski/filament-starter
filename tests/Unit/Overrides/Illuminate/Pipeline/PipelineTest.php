<?php

namespace Tests\Unit\Overrides\Illuminate\Pipeline;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Overrides\Illuminate\Pipeline\Handlers\ExampleHandler1;
use Tests\Unit\Overrides\Illuminate\Pipeline\Handlers\ExampleHandler2;
use Tests\Unit\Overrides\Illuminate\PipelineFactory;

final class PipelineTest extends TestCase
{
    public function test_pipeline_with_created_objects(): void
    {
        $pipeline = PipelineFactory::makePipeline();

        $handlers = [
            new ExampleHandler1,
            new ExampleHandler2,
        ];

        $result = $pipeline->through(...$handlers)->process('Test');

        $this->assertTrue($result === 'Test12');
    }

    public function test_pipeline_with_namespaces(): void
    {
        $pipeline = PipelineFactory::makePipeline();

        $handlers = [
            ExampleHandler1::class,
            ExampleHandler2::class,
        ];

        $result = $pipeline->through(...$handlers)->process('Test');

        $this->assertTrue($result === 'Test12');
    }
}
