<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Phu1237\LaravelSettings\Tests\TestCase;

class InstallCommandTest extends TestCase
{
    /** @test */
    function the_install_command_copies_the_configuration()
    {
        // make sure we're starting from a clean state
        if (File::exists(config_path('settings.php'))) {
            unlink(config_path('settings.php'));
        }

        $this->assertFalse(File::exists(config_path('settings.php')));

        Artisan::call('settings:install');

        $this->assertTrue(File::exists(config_path('settings.php')));
    }
}
