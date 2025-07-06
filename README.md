# Filament General Settings

A Filament plugin for managing general settings in a Laravel application.

## Features

- Manage general settings through a Filament interface
- Support for various data types (string, integer, float, boolean, array, json, date, time, datetime, url, email, emails, password)
- Dynamic form inputs based on data type
- Encryption support for password values
- Multilingual support (English and Spanish)
- Table prefix configuration

## Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher
- Filament 3.0 or higher

## Installation

1. Install the package via Composer:

```bash
composer require josefo727/filament-general-settings
```

2. Run the installation command:

```bash
php artisan filament-general-settings:install
```

3. Run the migrations:

```bash
php artisan migrate
```

## Configuration

After installation, you can publish the configuration file:

```bash
php artisan vendor:publish --tag=filament-general-settings-config
```

This will create a `filament-general-settings.php` file in your config directory. You can customize the following options:

```php
return [
    // Table configuration
    'table' => [
        'name' => 'general_settings',
        'prefix' => env('FILAMENT_GENERAL_SETTINGS_TABLE_PREFIX', ''),
    ],

    // Encryption configuration
    'encryption' => [
        'enabled' => true,
        'key' => env('FILAMENT_GENERAL_SETTINGS_ENCRYPTION_KEY', 'some_default_key'),
    ],

    // Password display configuration
    'show_passwords' => env('FILAMENT_GENERAL_SETTINGS_SHOW_PASSWORDS', false),

    // Navigation configuration
    'navigation' => [
        'group' => 'Settings',
        'icon' => 'heroicon-o-cog',
        'sort' => 1,
    ],
];
```

## Usage

### Accessing Settings

You can access settings in your application using the provided facade:

```php
use Josefo727\FilamentGeneralSettings\Facades\FilamentGeneralSettings;

// Get a setting value
$value = FilamentGeneralSettings::get('setting_name');

// Get a setting value with a default
$value = FilamentGeneralSettings::get('setting_name', 'default_value');

// Check if a setting exists
if (FilamentGeneralSettings::has('setting_name')) {
    // Do something
}

// Set a setting value
FilamentGeneralSettings::set('setting_name', 'value', 'string', 'Description');

// Remove a setting
FilamentGeneralSettings::remove('setting_name');
```

### Data Types

The package supports the following data types:

- `string`: Text values
- `integer`: Integer values
- `float`: Floating point values
- `boolean`: Boolean values (true/false)
- `array`: Array values (stored as comma-separated strings)
- `json`: JSON values
- `date`: Date values
- `time`: Time values
- `datetime`: Date and time values
- `url`: URL values
- `email`: Email values
- `emails`: Multiple email values (stored as comma-separated strings)
- `password`: Password values (can be encrypted)

### Translations

The package comes with English and Spanish translations. You can publish the translation files to customize them:

```bash
php artisan vendor:publish --tag=filament-general-settings-translations
```

## Testing

```bash
composer test
```

## License

This package is open-sourced software licensed under the MIT license.