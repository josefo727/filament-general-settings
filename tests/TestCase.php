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
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Josefo727\\FilamentGeneralSettings\\Tests\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            // Paquetes base de Laravel y Filament
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FormsServiceProvider::class,
            TablesServiceProvider::class,
            NotificationsServiceProvider::class,
            ActionsServiceProvider::class,
            InfolistsServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentServiceProvider::class,

            // Nuestro proveedor de servicios
            FilamentGeneralSettingsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // Configurar clave de aplicación para pruebas
        $app['config']->set('app.key', 'base64:'.base64_encode(
            'KaPdSgVkxX2bPTygJO38wgVagNJiGU3U'
        ));

        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Configurar el prefijo de tabla para asegurar que las pruebas usen el mismo nombre de tabla
        config()->set('filament-general-settings.table.prefix', '');
        config()->set('filament-general-settings.table.name', 'general_settings');

        // Configurar la encriptación para pruebas (desactivarla)
        config()->set('filament-general-settings.encryption.enabled', false);

        // Crear la tabla general_settings manualmente para las pruebas que no necesitan todas las migraciones
        $this->createGeneralSettingsTable();
    }

    /**
     * Crear la tabla general_settings para pruebas
     */
    protected function createGeneralSettingsTable()
    {
        if (! Schema::hasTable('general_settings')) {
            Schema::create('general_settings', function ($table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string');
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
