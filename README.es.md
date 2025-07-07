# Configuraciones Generales de Filament

[![English](https://img.shields.io/badge/lang-English-blue.svg)](README.md)
[![Español](https://img.shields.io/badge/lang-Español-red.svg)](README.es.md)

Un plugin de Filament para gestionar configuraciones generales en una aplicación Laravel.

## Características

- Gestión de configuraciones generales a través de una interfaz Filament
- Soporte para varios tipos de datos (string, integer, float, boolean, array, json, date, time, datetime, url, email, emails, password)
- Entradas de formulario dinámicas basadas en el tipo de dato
- Soporte de encriptación para valores de contraseña
- Soporte multilingüe (Inglés y Español)
- Configuración de prefijo de tabla

## Requisitos

- PHP 8.1 o superior
- Laravel 10.0 o superior
- Filament 3.0 o superior

## Instalación

1. Instala el paquete vía Composer:

    ```bash
    composer require josefo727/filament-general-settings
    ```

2. Ejecuta el comando de instalación:

    ```bash
    php artisan filament-general-settings:install
    ```

3. Ejecuta las migraciones:

    ```bash
    php artisan migrate
    ```

## Configuración

Después de la instalación, puedes publicar el archivo de configuración:

```bash
php artisan vendor:publish --tag=filament-general-settings-config
```

Esto creará un archivo `filament-general-settings.php` en tu directorio de configuración. Puedes personalizar las siguientes opciones:

```php
return [
    // Configuración de tabla
    'table' => [
        'name' => 'general_settings',
        'prefix' => env('FILAMENT_GENERAL_SETTINGS_TABLE_PREFIX', ''),
    ],

    // Configuración de encriptación
    'encryption' => [
        'enabled' => true,
        'key' => env('FILAMENT_GENERAL_SETTINGS_ENCRYPTION_KEY', 'some_default_key'),
    ],

    // Configuración de visualización de contraseñas
    'show_passwords' => env('FILAMENT_GENERAL_SETTINGS_SHOW_PASSWORDS', false),

    // Configuración de navegación
    'navigation' => [
        'group' => 'Settings',
        'icon' => 'heroicon-o-cog',
        'sort' => 1,
    ],
];
```

## Integración con el Panel de Administración de Filament

### Registrar el Plugin

Para añadir las Configuraciones Generales a tu Panel de Administración de Filament, necesitas registrar el plugin en tu `App\Providers\Filament\AdminPanelProvider`:

```php
use Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsPlugin;

// ...

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            FilamentGeneralSettingsPlugin::make(),
            // Otros plugins...
        ]);
}
```

### Añadir Alias (Opcional)

#### Laravel 10 y 11

Para un acceso más fácil al modelo GeneralSetting sin tener que importarlo, puedes añadir un alias en tu archivo `config/app.php`:

```php
'aliases' => [
    // ...
    'GeneralSetting' => Josefo727\FilamentGeneralSettings\Models\GeneralSetting::class,
],
```

#### Laravel 12

Para Laravel 12, puedes registrar el alias en tu `App\Providers\AppServiceProvider`:

```php
use Illuminate\Foundation\AliasLoader;
use Josefo727\FilamentGeneralSettings\Models\GeneralSetting;

public function register(): void
{
    $loader = AliasLoader::getInstance();
    $loader->alias('GeneralSetting', GeneralSetting::class);
}
```

## Uso

### Accediendo a las Configuraciones

Puedes acceder a las configuraciones en tu aplicación utilizando la fachada proporcionada:

```php
// Obtener un valor de configuración
$value = GeneralSetting::getValue('setting_name');

// Obtener un valor de configuración con un valor predeterminado
$value = GeneralSetting::getValue('setting_name', 'default_value');

// Usando la función helper para obtener un valor de configuración
$value = getSetting('setting_name');

// Usando la función helper para obtener un valor de configuración con un valor predeterminado
$value = getSetting('setting_name', 'default_value');

// Comprobar si existe una configuración
if (GeneralSetting::has('setting_name')) {
    // Hacer algo
}

// Establecer un valor de configuración
GeneralSetting::create([
    'name' => 'setting_name',
    'value' => 'value',
    'type' => 'string',
    'description' => 'Description'
]);

// Eliminar una configuración
GeneralSetting::remove('setting_name');
```

### Tipos de Datos

El paquete soporta los siguientes tipos de datos:

- `string`: Valores de texto
- `integer`: Valores enteros
- `float`: Valores de punto flotante
- `boolean`: Valores booleanos (verdadero/falso)
- `array`: Valores de array (almacenados como cadenas separadas por comas)
- `json`: Valores JSON
- `date`: Valores de fecha
- `time`: Valores de tiempo
- `datetime`: Valores de fecha y hora
- `url`: Valores URL
- `email`: Valores de correo electrónico
- `emails`: Múltiples valores de correo electrónico (almacenados como cadenas separadas por comas)
- `password`: Valores de contraseña (pueden ser encriptados)

### Traducciones

El paquete viene con traducciones en inglés y español. Puedes publicar los archivos de traducción para personalizarlos:

```bash
php artisan vendor:publish --tag=filament-general-settings-translations
```

## Pruebas

```bash
./vendor/bin/phpunit
```

## Licencia

Este paquete es software de código abierto licenciado bajo la licencia MIT.
