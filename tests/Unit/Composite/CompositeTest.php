<?php

namespace Tests\Unit\Composite;

use DG\BypassFinals;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Composite\Handlers\ExampleHandler1;
use Tests\Unit\Composite\Handlers\ExampleHandler2;

final class CompositeTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable(bypassReadOnly: false);
    }

    public function test_composite_with_created_objects(): void
    {
        $composite = new ExampleComposite;

        $handlers = [
            $this->createMock(ExampleHandler1::class),
            $this->createMock(ExampleHandler2::class),
        ];

        foreach ($handlers as $handler) {
            $handler->expects($this->once())->method('handle')->with('Test');
        }

        $composite->through(...$handlers)->process('Test');
    }
}
