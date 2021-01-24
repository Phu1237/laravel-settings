<?php

namespace Phu1237\LaravelSettings\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected $signature = 'settings:publish';
    protected $description = 'Publish the Laravel Settings files';

    public function handle()
    {
        $this->call('vendor:publish', [
            '--provider' => "Phu1237\LaravelSettings\SettingsServiceProvider",
        ]);
    }
}
