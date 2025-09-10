<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

error_reporting(E_ALL);

class ExampleTest extends TestCase
{
    #[Group('fail')]
    public function test_fail_example()
    {
        $this->assertFalse(true);
    }

    #[Group('todo')]
    public function test_todo_example()
    {
        $this->markTestSkipped('__TODO__');
    }

    public function test_basic_test()
    {
        $this->assertTrue(true);
    }

    #[Group('notices')]
    public function test_user_notice()
    {
        trigger_error('This is a user notice');

        $this->assertTrue(true);
    }

    #[Group('notices')]
    public function test_user_notice_two()
    {
        trigger_error('This is another user notice');

        $this->assertTrue(true);
    }

    #[Group('warnings')]
    public function test_warning()
    {
        $this->blabla;

        $this->assertTrue(true);
    }

    #[Group('warnings')]
    public function test_user_warning()
    {
        trigger_error('This is a user warning', E_USER_WARNING);

        $this->assertTrue(true);
    }

    #[Group('deprecations')]
    public function test_deprecation()
    {
        str_contains(null, null);

        $this->assertTrue(true);
    }

    #[Group('deprecations')]
    public function test_user_deprecation()
    {
        trigger_deprecation('foo', '1.0', 'This is a deprecation description');

        $this->assertTrue(true);
    }
}
