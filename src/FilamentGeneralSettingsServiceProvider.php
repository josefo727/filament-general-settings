<?php

namespace Josefo727\FilamentGeneralSettings;

use Josefo727\FilamentGeneralSettings\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentGeneralSettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-general-settings')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasCommand(InstallCommand::class)
            ->hasMigration('create_general_settings_table');
    }

    /**
     * @return void
     *
     * @throws InvalidPackage
     */
    public function register()
    {
        parent::register();
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/filament-general-settings.php',
            'filament-general-settings'
        );

        // Register the service the package provides.
        $this->app->singleton('filament-general-settings', function ($app) {
            return new FilamentGeneralSettings;
        });
    }

    public function boot(): void
    {
        parent::boot();

        // Cargar traducciones explícitamente
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'filament-general-settings');

        // Publicar traducciones
        $this->publishes([
            __DIR__.'/../lang' => lang_path('vendor/filament-general-settings'),
        ], 'filament-general-settings-translations');
    }

    public static function getTableName(): string
    {
        $prefix = config('filament-general-settings.table.prefix', '');
        $name = config('filament-general-settings.table.name', 'general_settings');

        return $prefix ? $prefix.'_'.$name : $name;
    }
}
