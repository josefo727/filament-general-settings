<?php

namespace Josefo727\FilamentGeneralSettings;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentGeneralSettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filament-general-settings')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews()
            ->hasMigrations('create_general_settings_table')
            ->runsMigrations()
            ->hasInstallCommand(function (InstallCommand $installCommand) {
                $installCommand
                    ->startWith(function (InstallCommand $command) {
                        $command->info('Installing Filament General Settings...');
                    })
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->endWith(function (InstallCommand $command) {
                        $command->info('Filament General Settings installed successfully.');
                    });
            });
    }

    public static function getTableName(): string
    {
        $prefix = config('filament-general-settings.table.prefix', '');
        $name = config('filament-general-settings.table.name', 'general_settings');

        return $prefix ? $prefix.'_'.$name : $name;
    }
}
