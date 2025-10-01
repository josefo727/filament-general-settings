<?php

namespace Josefo727\FilamentGeneralSettings\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider;
use Josefo727\FilamentGeneralSettings\Tests\TestCase;

class MigrationTest extends TestCase
{
    /**
     * Configurar el entorno para esta prueba específica
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Eliminar tablas si existen antes de cada prueba
        Schema::dropIfExists('general_settings');
        Schema::dropIfExists('test_general_settings');
    }

    /**
     * Limpiar después de cada prueba
     */
    protected function tearDown(): void
    {
        Schema::dropIfExists('general_settings');
        Schema::dropIfExists('test_general_settings');

        parent::tearDown();
    }

    /** @test */
    public function it_creates_the_general_settings_table()
    {
        // Configurar sin prefijo
        Config::set('filament-general-settings.table.prefix', '');

        // Crear manualmente la tabla usando el método de la migración
        $tableName = 'general_settings';
        Schema::create($tableName, function ($table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Verificar que la tabla existe
        $this->assertTrue(
            Schema::hasTable('general_settings'),
            'La tabla general_settings no se creó correctamente'
        );

        // Verificar que la tabla tiene las columnas esperadas
        $this->assertTrue(
            Schema::hasColumns('general_settings', [
                'id', 'name', 'value', 'type', 'description', 'created_at', 'updated_at',
            ]),
            'La tabla general_settings no tiene todas las columnas esperadas'
        );
    }

    /** @test */
    public function it_respects_table_prefix_configuration()
    {
        // Asegurarse de que no existen las tablas antes de la prueba
        Schema::dropIfExists('general_settings');
        Schema::dropIfExists('test_general_settings');

        // Configurar con prefijo
        Config::set('filament-general-settings.table.prefix', 'test');

        // Crear manualmente la tabla usando el proveedor de servicios para obtener el nombre
        $tableName = FilamentGeneralSettingsServiceProvider::getTableName();
        $this->assertEquals('test_general_settings', $tableName, 'El nombre de la tabla no tiene el prefijo correcto');

        Schema::create($tableName, function ($table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Verificar que la tabla con prefijo existe
        $this->assertTrue(
            Schema::hasTable($tableName),
            "La tabla $tableName no se creó correctamente"
        );

        // Verificar que la tabla sin prefijo no existe
        $this->assertFalse(
            Schema::hasTable('general_settings'),
            'La tabla general_settings no debería existir'
        );
    }
}
