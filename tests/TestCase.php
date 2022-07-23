<?php

namespace AntoninMasek\Hashids\Tests;

use AntoninMasek\Hashids\HashidsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'AntoninMasek\\Hashids\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            HashidsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }

    private function setUpDatabase(Application $app)
    {
        $tables = [
            'test_models',
            'binding_test_models',
            'salt_test_models',
            'alphabet_test_models',
            'min_length_test_models',
        ];

        collect($tables)->each(function (string $table) use ($app) {
            $app['db']->connection()->getSchemaBuilder()->create($table, function (Blueprint $table) {
                $table->increments('id');
                $table->string('hash_id')->nullable()->unique();
            });
        });
    }
}
