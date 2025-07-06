<?php

namespace Josefo727\FilamentGeneralSettings;

use Illuminate\Support\ServiceProvider;
use Josefo727\FilamentGeneralSettings\Commands\InstallCommand;

class FilamentGeneralSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'filament-general-settings');

        $this->publishes([
            __DIR__ . '/../config/filament-general-settings.php' => config_path('filament-general-settings.php'),
        ], 'filament-general-settings-config');

        $this->publishes([
            __DIR__ . '/../lang' => lang_path('vendor/filament-general-settings'),
        ], 'filament-general-settings-translations');

        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/filament-general-settings.php',
            'filament-general-settings'
        );

        // Register the service the package provides.
        $this->app->singleton('filament-general-settings', function ($app) {
            return new FilamentGeneralSettings;
        });
    }

    /**
     * Get the table name with prefix if configured.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        $prefix = config('filament-general-settings.table.prefix', '');
        $name = config('filament-general-settings.table.name', 'general_settings');

        return $prefix ? $prefix . '_' . $name : $name;
    }
}