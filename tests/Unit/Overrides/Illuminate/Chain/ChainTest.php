<?php

namespace Tests\Unit\Overrides\Illuminate\Chain;

use DG\BypassFinals;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Overrides\Illuminate\Chain\Handlers\ExampleHandler1;
use Tests\Unit\Overrides\Illuminate\Chain\Handlers\ExampleHandler2;
use Tests\Unit\Overrides\Illuminate\PipelineFactory;

final class ChainTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable(bypassReadOnly: false);
    }

    public function test_chain_with_created_objects(): void
    {
        $chain = PipelineFactory::makePipeline();

        $handlers = [
            $this->createMock(ExampleHandler1::class),
            $this->createMock(ExampleHandler2::class),
        ];

        $handlers[0]->expects($this->once())->method('handle')->with('Test1');
        $handlers[1]->expects($this->never())->method('handle');

        $chain->through(...$handlers)->process('Test1');
    }

    public function test_chain_with_namespaces(): void
    {
        $container = new Container;

        /** @var array<int, \PHPUnit\Framework\MockObject\MockObject> $handlers */
        $handlers = [
            $this->createMock(ExampleHandler1::class),
            $this->createMock(ExampleHandler2::class),
        ];

        $handlers[0]->expects($this->once())->method('handle')->with('Test1');
        $handlers[1]->expects($this->never())->method('handle');

        $container->instance(ExampleHandler1::class, $handlers[0]);
        $container->instance(ExampleHandler2::class, $handlers[1]);

        $chain = PipelineFactory::makePipeline($container);

        $handlers = [
            ExampleHandler1::class,
            ExampleHandler2::class,
        ];

        $chain->through(...$handlers)->process('Test1');
    }
}
