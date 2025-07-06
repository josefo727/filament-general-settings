<?php

namespace Josefo727\FilamentGeneralSettings\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider;
use Josefo727\FilamentGeneralSettings\Tests\TestCase;
use Josefo727\FilamentGeneralSettings\Tests\User;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::create(['email' => 'test4@example.com', 'name' => 'Test User 4', 'password' => 'password']);
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_creates_the_general_settings_table()
    {
        // Run the migrations
        $this->artisan('migrate')->assertExitCode(0);

        // Check if the table exists
        $tableName = FilamentGeneralSettingsServiceProvider::getTableName();
        $this->assertTrue(Schema::hasTable($tableName));

        // Check if the table has the expected columns
        $this->assertTrue(Schema::hasColumns($tableName, [
            'id',
            'name',
            'value',
            'description',
            'type',
            'created_at',
            'updated_at',
        ]));
    }

    /** @test */
    public function it_respects_table_prefix_configuration()
    {
        // Set a prefix in the configuration
        Config::set('filament-general-settings.table.prefix', 'test');

        // Drop existing tables to ensure clean state
        Schema::dropIfExists('general_settings');
        Schema::dropIfExists('test_general_settings');

        // Run the migrations
        $this->artisan('migrate:fresh')->assertExitCode(0);

        // Check if the prefixed table exists
        $tableName = FilamentGeneralSettingsServiceProvider::getTableName();
        $this->assertEquals('test_general_settings', $tableName);
        $this->assertTrue(Schema::hasTable($tableName));

        // Check if the non-prefixed table does not exist
        $this->assertFalse(Schema::hasTable('general_settings'));
    }
}
