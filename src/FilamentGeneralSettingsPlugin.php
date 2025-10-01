<?php

namespace Josefo727\FilamentGeneralSettings;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource;

class FilamentGeneralSettingsPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-general-settings';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                GeneralSettingResource::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
