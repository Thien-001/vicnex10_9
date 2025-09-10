<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('environmentCustomVariables')]
class EnvironmentCustomVariablesTest extends TestCase
{
    #[Group('environmentNoCVPhpunit')]
    public function test_environment_no_custom_variables_phpunit()
    {
        $this->assertEquals(null, env('LARAVEL_PARALLEL_TESTING'));
        $this->assertEquals(null, env('LARAVEL_PARALLEL_TESTING_RECREATE_DATABASES'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PHPUNIT'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PARALLEL'));
    }

    #[Group('environmentNoCVParallel')]
    public function test_environment_no_custom_variables_parallel()
    {
        $this->assertEquals(1, env('LARAVEL_PARALLEL_TESTING'));
        $this->assertEquals(null, env('LARAVEL_PARALLEL_TESTING_RECREATE_DATABASES'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PHPUNIT'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PARALLEL'));
    }

    #[Group('environmentNoCVParallelRecreate')]
    public function test_environment_no_custom_variables_parallel_with_recreate()
    {
        $this->assertEquals(1, env('LARAVEL_PARALLEL_TESTING'));
        $this->assertEquals(1, env('LARAVEL_PARALLEL_TESTING_RECREATE_DATABASES'));
        $this->assertEquals(null, env('LARAVEL_PARALLEL_TESTING_DROP_DATABASES'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PHPUNIT'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PARALLEL'));
    }

    #[Group('environmentNoCVParallelDrop')]
    public function test_environment_no_custom_variables_parallel_with_drop()
    {
        $this->assertEquals(1, env('LARAVEL_PARALLEL_TESTING'));
        $this->assertEquals(1, env('LARAVEL_PARALLEL_TESTING_DROP_DATABASES'));
        $this->assertEquals(null, env('LARAVEL_PARALLEL_TESTING_RECREATE_DATABASES'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PHPUNIT'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PARALLEL'));
    }

    #[Group('environmentCVPhpunit')]
    public function test_environment_custom_variables_phpunit()
    {
        $this->assertEquals(null, env('LARAVEL_PARALLEL_TESTING'));
        $this->assertEquals(null, env('LARAVEL_PARALLEL_TESTING_RECREATE_DATABASES'));
        $this->assertEquals(1, env('CUSTOM_ENV_VARIABLE'));
        $this->assertEquals(1, env('CUSTOM_ENV_VARIABLE_FOR_PHPUNIT'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PARALLEL'));
    }

    #[Group('environmentCVParallel')]
    public function test_environment_custom_variables_parallel()
    {
        $this->assertEquals(1, env('LARAVEL_PARALLEL_TESTING'));
        $this->assertEquals(null, env('LARAVEL_PARALLEL_TESTING_RECREATE_DATABASES'));
        $this->assertEquals(1, env('CUSTOM_ENV_VARIABLE'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PHPUNIT'));
        $this->assertEquals(1, env('CUSTOM_ENV_VARIABLE_FOR_PARALLEL'));
    }

    #[Group('environmentCVParallelRecreate')]
    public function test_environment_custom_variables_parallel_with_recreate()
    {
        $this->assertEquals(1, env('LARAVEL_PARALLEL_TESTING'));
        $this->assertEquals(1, env('LARAVEL_PARALLEL_TESTING_RECREATE_DATABASES'));
        $this->assertEquals(1, env('CUSTOM_ENV_VARIABLE'));
        $this->assertEquals(null, env('LARAVEL_PARALLEL_TESTING_DROP_DATABASES'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PHPUNIT'));
        $this->assertEquals(1, env('CUSTOM_ENV_VARIABLE_FOR_PARALLEL'));
    }

    #[Group('environmentCVParallelDrop')]
    public function test_environment_custom_variables_parallel_with_drop()
    {
        $this->assertEquals(1, env('LARAVEL_PARALLEL_TESTING'));
        $this->assertEquals(1, env('CUSTOM_ENV_VARIABLE'));
        $this->assertEquals(1, env('LARAVEL_PARALLEL_TESTING_DROP_DATABASES'));
        $this->assertEquals(null, env('LARAVEL_PARALLEL_TESTING_RECREATE_DATABASES'));
        $this->assertEquals(null, env('CUSTOM_ENV_VARIABLE_FOR_PHPUNIT'));
        $this->assertEquals(1, env('CUSTOM_ENV_VARIABLE_FOR_PARALLEL'));
    }
}
