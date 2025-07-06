<?php

namespace Josefo727\FilamentGeneralSettings\Tests;

use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory as EloquentFactory;
use Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Load and run migrations from the test directory first
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->artisan('migrate');

        // Create a user directly instead of using the factory
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'test@example.com';
        $user->password = bcrypt('password');
        $user->save();

        $this->actingAs($user);
    }

    protected function getPackageProviders($app): array
    {
        return [
            FilamentServiceProvider::class,
            SupportServiceProvider::class,
            FormsServiceProvider::class,
            TablesServiceProvider::class,
            NotificationsServiceProvider::class,
            ActionsServiceProvider::class,
            InfolistsServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentGeneralSettingsServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Set the table prefix for testing if needed
        $app['config']->set('filament-general-settings.table.prefix', '');

        // Configure Filament panel
        $app['config']->set('filament.default_filesystem_disk', 'public');
        $app['config']->set('filament.auth.guard', 'web');
        $app['config']->set('filament.auth.provider', 'users');
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        // Register a default panel
        $app['config']->set('filament.panels.admin', [
            'id' => 'admin',
            'path' => 'admin',
            'middleware' => ['web'],
            'default_route' => 'dashboard',
        ]);
    }
}
