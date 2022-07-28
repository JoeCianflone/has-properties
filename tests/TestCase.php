<?php declare(strict_types=1);

namespace JoeCianflone\HasProperties\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use JoeCianflone\HasProperties\HasPropertiesServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function getEnvironmentSetUp($app): void
    {
        // config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_skeleton_table.php.stub';
        $migration->up();
        */
    }

    protected function getPackageProviders($app)
    {
        return [
            HasPropertiesServiceProvider::class,
        ];
    }
}
