<?php

namespace Josefo727\FilamentGeneralSettings\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Josefo727\FilamentGeneralSettings\Models\GeneralSetting;
use Josefo727\FilamentGeneralSettings\Tests\TestCase;
use Josefo727\FilamentGeneralSettings\Tests\User;

class GeneralSettingModelTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::create(['email' => 'test3@example.com', 'name' => 'Test User 3', 'password' => 'password']);
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_create_a_setting()
    {
        $setting = GeneralSetting::create([
            'name' => 'test_setting',
            'value' => 'test_value',
            'description' => 'Test setting description',
            'type' => 'string',
        ]);

        $this->assertDatabaseHas(\Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider::getTableName(), [
            'name' => 'test_setting',
            'value' => 'test_value',
            'description' => 'Test setting description',
            'type' => 'string',
        ]);

        $this->assertEquals('test_setting', $setting->name);
        $this->assertEquals('test_value', $setting->value);
        $this->assertEquals('Test setting description', $setting->description);
        $this->assertEquals('string', $setting->type);
    }

    /** @test */
    public function it_can_update_a_setting()
    {
        $setting = GeneralSetting::create([
            'name' => 'test_setting',
            'value' => 'test_value',
            'description' => 'Test setting description',
            'type' => 'string',
        ]);

        $setting->update([
            'value' => 'updated_value',
            'description' => 'Updated description',
        ]);

        $this->assertDatabaseHas(\Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider::getTableName(), [
            'name' => 'test_setting',
            'value' => 'updated_value',
            'description' => 'Updated description',
            'type' => 'string',
        ]);
    }

    /** @test */
    public function it_can_delete_a_setting()
    {
        $setting = GeneralSetting::create([
            'name' => 'test_setting',
            'value' => 'test_value',
            'description' => 'Test setting description',
            'type' => 'string',
        ]);

        $setting->delete();

        $this->assertDatabaseMissing(\Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider::getTableName(), [
            'name' => 'test_setting',
        ]);
    }

    /** @test */
    public function it_can_get_a_setting_value_by_name()
    {
        GeneralSetting::create([
            'name' => 'test_setting',
            'value' => 'test_value',
            'description' => 'Test setting description',
            'type' => 'string',
        ]);

        $value = GeneralSetting::getValue('test_setting');

        $this->assertEquals('test_value', $value);
    }

    /** @test */
    public function it_returns_null_for_nonexistent_setting()
    {
        $value = GeneralSetting::getValue('nonexistent_setting');

        $this->assertNull($value);
    }

    /** @test */
    public function it_casts_values_according_to_type()
    {
        // String
        GeneralSetting::create([
            'name' => 'string_setting',
            'value' => '123',
            'type' => 'string',
        ]);

        // Integer
        GeneralSetting::create([
            'name' => 'integer_setting',
            'value' => '123',
            'type' => 'integer',
        ]);

        // Float
        GeneralSetting::create([
            'name' => 'float_setting',
            'value' => '123.45',
            'type' => 'float',
        ]);

        // Boolean
        GeneralSetting::create([
            'name' => 'boolean_setting',
            'value' => 'true',
            'type' => 'boolean',
        ]);

        // JSON
        GeneralSetting::create([
            'name' => 'json_setting',
            'value' => '{"key":"value"}',
            'type' => 'json',
        ]);

        $this->assertIsString(GeneralSetting::getValue('string_setting'));
        $this->assertEquals('123', GeneralSetting::getValue('string_setting'));

        $this->assertIsInt(GeneralSetting::getValue('integer_setting'));
        $this->assertEquals(123, GeneralSetting::getValue('integer_setting'));

        $this->assertIsFloat(GeneralSetting::getValue('float_setting'));
        $this->assertEquals(123.45, GeneralSetting::getValue('float_setting'));

        $this->assertIsBool(GeneralSetting::getValue('boolean_setting'));
        $this->assertTrue(GeneralSetting::getValue('boolean_setting'));

        $this->assertIsArray(GeneralSetting::getValue('json_setting'));
        $this->assertEquals(['key' => 'value'], GeneralSetting::getValue('json_setting'));
    }
}
