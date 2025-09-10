<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_skipped_example()
    {
        $this->markTestSkipped('This is a skip description');
    }

    public function test_incomplete_example()
    {
        $this->markTestIncomplete('This is a incomplete description');
    }

    public function test_risky_example()
    {
        // ..
    }

    public function test_deprecation_example()
    {
        trigger_deprecation('foo', '1.0', 'This is a deprecation description');

        $this->assertTrue(true);
    }

    public function test_pass_example()
    {
        static::assertTrue(true);
    }
}
