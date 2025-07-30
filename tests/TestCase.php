<?php

namespace LaravelBits\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;

use function Orchestra\Testbench\workbench_path;

abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(
            workbench_path('database/migrations')
        );
    }
}
