<?php

namespace Phu1237\LaravelSettings;

use Illuminate\Support\ServiceProvider;
use Phu1237\LaravelSettings\Components\Input;
use Phu1237\LaravelSettings\Console\InstallCommand;
use Phu1237\LaravelSettings\Console\PublishCommand;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('settings', function () {
            return new SettingManager();
        });
        $this->mergeConfigFrom(
            __DIR__ . '/../config/settings.php', 'settings'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                PublishCommand::class
            ]);
            // Export the config
            $this->publishes([
                __DIR__ . '/../config/settings.php' => config_path('settings.php')
            ], 'config');
            // Export the views
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/settings'),
            ], 'views');
            // Export the tests
            $this->publishes([
                __DIR__ . '/../stubs/tests/Feature' => base_path('tests/Feature')
            ], 'tests');
            // Load the migration
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
        // Load the view
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'settings');
        // Load the view components
        $this->loadViewComponentsAs('settings', [
            Input::class,
        ]);
    }
}
