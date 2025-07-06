<?php

namespace Josefo727\FilamentGeneralSettings;

class FilamentGeneralSettings
{
    /**
     * Get a setting value by name.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        $model = config('filament-general-settings.model', \Josefo727\FilamentGeneralSettings\Models\GeneralSetting::class);
        
        return $model::getValue($name) ?? $default;
    }

    /**
     * Set a setting value.
     *
     * @param string $name
     * @param mixed $value
     * @param string|null $type
     * @param string|null $description
     * @return \Josefo727\FilamentGeneralSettings\Models\GeneralSetting
     */
    public function set(string $name, $value, ?string $type = 'string', ?string $description = null)
    {
        $model = config('filament-general-settings.model', \Josefo727\FilamentGeneralSettings\Models\GeneralSetting::class);
        
        $setting = $model::query()->firstWhere('name', $name);
        
        if ($setting) {
            return $model::updateSetting($setting, [
                'value' => $value,
                'type' => $type,
                'description' => $description ?? $setting->description,
            ]);
        }
        
        return $model::create([
            'name' => $name,
            'value' => $value,
            'type' => $type,
            'description' => $description,
        ]);
    }

    /**
     * Check if a setting exists.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        $model = config('filament-general-settings.model', \Josefo727\FilamentGeneralSettings\Models\GeneralSetting::class);
        
        return $model::query()->where('name', $name)->exists();
    }

    /**
     * Remove a setting.
     *
     * @param string $name
     * @return bool
     */
    public function remove(string $name): bool
    {
        $model = config('filament-general-settings.model', \Josefo727\FilamentGeneralSettings\Models\GeneralSetting::class);
        
        $setting = $model::query()->firstWhere('name', $name);
        
        if ($setting) {
            return $setting->delete();
        }
        
        return false;
    }
}