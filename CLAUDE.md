# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A Filament v4 plugin for Laravel 11.28+ that provides a settings management system with support for multiple data types, encryption, and a Filament admin interface.

**Package namespace**: `Josefo727\FilamentGeneralSettings`

**Version**: 2.0.0 (Filament 4 compatible)

## Commands

### Testing
```bash
# Run all tests
./vendor/bin/phpunit

# Run with coverage report
composer test-coverage

# Run specific test suite
./vendor/bin/phpunit --testsuite=Unit
./vendor/bin/phpunit --testsuite=Feature
```

### Code Quality
```bash
# Format code with Laravel Pint
composer format
# or
./vendor/bin/pint

# Run static analysis with PHPStan (Level 5)
composer analyse
# or
./vendor/bin/phpstan analyse
```

### Installation in Host App
```bash
# Install command (runs migrations and publishes assets)
php artisan filament-general-settings:install

# Publish config separately
php artisan vendor:publish --tag=filament-general-settings-config

# Publish translations
php artisan vendor:publish --tag=filament-general-settings-translations
```

## Architecture

### Core Components

**GeneralSetting Model** (`src/Models/GeneralSetting.php`)
- Eloquent model with dynamic table name support via `FilamentGeneralSettingsServiceProvider::getTableName()`
- Automatic encryption/decryption for password type fields via model events (boot method)
- Static methods for settings management: `getValue()`, `has()`, `remove()`
- Custom `create()` and `updateSetting()` methods that handle validation and data transformation

**DataTypeService** (`src/Services/DataTypeService.php`)
- Manages 12 supported data types: string, integer, float, boolean, array, json, date, time, datetime, url, email, emails, password
- Each type defines: name (translated), validation rules, and `prepareForUse()` callback
- Methods: `castForUse()` (storage → application), `castForStorage()` (application → storage)

**EncryptionService** (`src/Services/EncryptionService.php`)
- Wrapper around Laravel's `Crypt` facade for password field encryption
- Graceful decryption failure handling (returns original value if decryption fails)

**GeneralSettingResource** (`src/Filament/Resources/GeneralSettingResource.php`)
- Filament resource with dynamic form fields based on selected data type
- Uses reactive Select component for type field - changing type updates the value input field
- Navigation configuration pulled from config: group, icon, sort order

**FilamentGeneralSettingsPlugin** (`src/FilamentGeneralSettingsPlugin.php`)
- Filament plugin class that registers the GeneralSettingResource
- Must be registered in panel provider: `FilamentGeneralSettingsPlugin::make()`

### Helper Functions

Global `getSetting($key, $default = null)` function in `src/helpers.php` - delegates to `GeneralSetting::getValue()`

### Configuration

Config file (`config/filament-general-settings.php`) controls:
- Table name and prefix
- Encryption settings (enabled/disabled, key)
- Password display behavior in UI
- Navigation settings (group, icon, sort)

### Data Flow for Settings

1. **Create/Update**: Form → validation via `GeneralSetting::getValidationRules()` → `prepareAttributesForSaving()` transforms arrays/emails to CSV → model event encrypts passwords → database
2. **Retrieve**: Database → `DataTypeService::castForUse()` decrypts passwords and casts to appropriate PHP type → application
3. **Display**: Model → `getValueForDisplayAttribute()` masks passwords (if configured) and formats values → table column

### Key Design Patterns

- **Service-based type system**: DataTypeService centralizes all type definitions, validation, and casting logic
- **Model event hooks**: Encryption happens automatically in model boot events, not in controllers/resources
- **Dynamic forms**: Filament form schema changes based on selected type using reactive components and closures
- **Table name flexibility**: Dynamic table name via service provider method allows prefix configuration

### Translation Support

Translations in `lang/en/` and `lang/es/` for:
- `general.php` - UI labels, navigation, field names
- `types.php` - Data type display names

## Testing

Tests use Orchestra Testbench with SQLite in-memory database. Test structure:
- **Unit tests**: DataTypeService, database connections
- **Feature tests**: Model methods, migrations, form validation

PHPUnit configuration sets test environment variables for database and cache drivers.
