<?php

declare(strict_types=1);

namespace Tests\Unit;

use NunoMaduro\Collision\Handler;
use NunoMaduro\Collision\Writer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\ConsoleOutput;

class HandlerTest extends TestCase
{
    #[Test]
    public function it_sets_the_output(): void
    {
        $output = new ConsoleOutput;
        $handler = new Handler;

        $handler->setOutput($output);
        $this->assertSame($output, $handler->getWriter()->getOutput());
    }

    #[Test]
    public function it_gets_the_writer(): void
    {
        $writer = new Writer;
        $handler = new Handler($writer);

        $this->assertEquals($handler->getWriter(), $writer);
    }
}
