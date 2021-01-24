<?php

namespace Phu1237\LaravelSettings\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'settings:install';
    protected $description = 'Install the Laravel Settings';

    public function handle()
    {
        $this->info('Installing Laravel Settings...');

        $this->call('migrate');

        $this->info('Installed Laravel Settings');
    }
}
