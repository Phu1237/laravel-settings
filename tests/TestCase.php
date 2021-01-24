<?php

namespace Phu1237\LaravelSettings\Tests;

use Phu1237\LaravelSettings\SettingsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            SettingsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
        include_once __DIR__ . '../database/migrations/2021_01_15_183104_create_settings_table.php';
        // run the up() method of that migration class
        (new \CreateSettingsTable())->up();
    }
}
