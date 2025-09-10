<?php

declare(strict_types=1);

namespace TestCaseWithStdoutOutput;

use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase
{
    public function test_with_output()
    {
        echo 'Foo';

        $this->assertTrue(true);
    }

    public function test_nothing_special()
    {
        // This shouldn't have any output
        $this->assertTrue(true);
    }

    public function test_with_no_output()
    {
        $this->expectOutputRegex('/Bar/');

        var_dump('Bar');
    }
}
