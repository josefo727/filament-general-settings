# Filament General Settings

[![English](https://img.shields.io/badge/lang-English-blue.svg)](README.md)
[![Español](https://img.shields.io/badge/lang-Español-red.svg)](README.es.md)

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

## Integration with Filament Admin Panel

### Register the Plugin

To add the General Settings to your Filament Admin Panel, you need to register the plugin in your `App\Providers\Filament\AdminPanelProvider`:

```php
use Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsPlugin;

// ...

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            FilamentGeneralSettingsPlugin::make(),
            // Other plugins...
        ]);
}
```

### Add Alias (Optional)

#### Laravel 10 and 11

For easier access to the GeneralSetting model without having to import it, you can add an alias in your `config/app.php` file:

```php
'aliases' => [
    // ...
    'GeneralSetting' => Josefo727\FilamentGeneralSettings\Models\GeneralSetting::class,
],
```

#### Laravel 12

For Laravel 12, you can register the alias in your `App\Providers\AppServiceProvider`:

```php
use Illuminate\Foundation\AliasLoader;
use Josefo727\FilamentGeneralSettings\Models\GeneralSetting;

public function register(): void
{
    $loader = AliasLoader::getInstance();
    $loader->alias('GeneralSetting', GeneralSetting::class);
}
```

## Usage

### Accessing Settings

You can access settings in your application using the provided facade:

```php
// Get a setting value
$value = GeneralSetting::getValue('setting_name');

// Get a setting value with a default
$value = GeneralSetting::getValue('setting_name', 'default_value');

// Using the helper function to get a setting value
$value = getSetting('setting_name');

// Using the helper function to get a setting value with a default
$value = getSetting('setting_name', 'default_value');

// Check if a setting exists
if (GeneralSetting::has('setting_name')) {
    // Do something
}

// Set a setting value
GeneralSetting::create([
    'name' => 'setting_name',
    'value' => 'value',
    'type' => 'string',
    'description' => 'Description'
]);

// Remove a setting
GeneralSetting::remove('setting_name');
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
./vendor/bin/phpunit
```

## License

This package is open-sourced software licensed under the MIT license.
