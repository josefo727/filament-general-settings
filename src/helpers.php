<?php

if (!function_exists('getSetting')) {
    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    function getSetting(string $key, mixed $default = null): mixed
    {
        $value = \Josefo727\FilamentGeneralSettings\Models\GeneralSetting::getValue($key);
        return $value ?? $default;
    }
}