<?php

namespace Josefo727\FilamentGeneralSettings\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'filament-general-settings:install';

    protected $description = 'Install the Filament General Settings package';

    public function handle(): int
    {
        $this->info('Installing Filament General Settings...');

        // Publish configuration
        $this->info('Publishing configuration...');
        $this->publishConfiguration();

        // Publish translations
        $this->info('Publishing translations...');
        $this->publishTranslations();

        // Run migrations
        $this->info('Running migrations...');
        $this->runMigrations();

        $this->info('Filament General Settings installed successfully.');

        return self::SUCCESS;
    }

    private function publishConfiguration(): void
    {
        $this->callSilently('vendor:publish', [
            '--tag' => 'filament-general-settings-config',
        ]);
    }

    private function publishTranslations(): void
    {
        $this->callSilently('vendor:publish', [
            '--tag' => 'filament-general-settings-translations',
        ]);
    }

    private function runMigrations(): void
    {
        $this->callSilently('migrate');
    }
}
