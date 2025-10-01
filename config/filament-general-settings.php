<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify the table name for general settings.
    | If you provide a prefix, the table name will be prefixed.
    | Example: prefix = 'custom', table name = 'custom_general_settings'
    |
    */
    'table' => [
        'name' => 'general_settings',
        'prefix' => env('FILAMENT_GENERAL_SETTINGS_TABLE_PREFIX', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Encryption Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify if password values should be encrypted.
    |
    */
    'encryption' => [
        'enabled' => true,
        'key' => env('FILAMENT_GENERAL_SETTINGS_ENCRYPTION_KEY', 'some_default_key'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Display Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify if password values should be displayed.
    |
    */
    'show_passwords' => env('FILAMENT_GENERAL_SETTINGS_SHOW_PASSWORDS', false),

    /*
    |--------------------------------------------------------------------------
    | Navigation Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify the navigation settings for the Filament panel.
    |
    */
    'navigation' => [
        'group' => 'Settings',
        'icon' => 'heroicon-o-cog',
        'sort' => 1,
    ],
];
