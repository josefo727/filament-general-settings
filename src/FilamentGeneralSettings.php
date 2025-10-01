<?php

namespace Josefo727\FilamentGeneralSettings;

use Josefo727\FilamentGeneralSettings\Models\GeneralSetting;

class FilamentGeneralSettings
{
    protected string $modelClass;

    public function __construct()
    {
        $this->modelClass = config('filament-general-settings.model', GeneralSetting::class);
    }

    /**
     * Get a setting value by name.
     *
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->modelClass::getValue($name) ?? $default;
    }

    /**
     * Set a setting value.
     *
     * @param  mixed  $value
     * @return GeneralSetting
     */
    public function set(string $name, $value, ?string $type = 'string', ?string $description = null)
    {
        $setting = $this->modelClass::query()->firstWhere('name', $name);

        if ($setting) {
            return $this->modelClass::updateSetting($setting, [
                'value' => $value,
                'type' => $type,
                'description' => $description ?? $setting->description,
            ]);
        }

        return $this->modelClass::create([
            'name' => $name,
            'value' => $value,
            'type' => $type,
            'description' => $description,
        ]);
    }

    /**
     * Check if a setting exists.
     */
    public function has(string $name): bool
    {
        return $this->modelClass::query()->where('name', $name)->exists();
    }

    /**
     * Remove a setting.
     */
    public function remove(string $name): bool
    {
        $setting = $this->modelClass::query()->firstWhere('name', $name);

        if ($setting) {
            return $setting->delete();
        }

        return false;
    }
}
