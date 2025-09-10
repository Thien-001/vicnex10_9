<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('environmentTesting')]
class EnvironmentTestingTest extends TestCase
{
    #[Test]
    public function variable_only_in_dot_env()
    {
        $this->assertEquals(null, env('VAR_IN_DOT_ENV'));
        $this->assertEquals('VAL_IN_DOT_ENV_TESTING', env('VAR_IN_DOT_ENV_TESTING'));
    }

    #[Test]
    public function variable_only_in_phpunit()
    {
        $this->assertEquals('VAL_IN_PHPUNIT', env('VAR_IN_PHPUNIT'));
    }

    #[Test]
    public function variable_in_dot_env_but_overridden_in_phpunit()
    {
        $this->assertEquals('VAL_OVERRIDDEN_IN_PHPUNIT', env('VAR_OVERRIDDEN_IN_PHPUNIT'));
    }
}
