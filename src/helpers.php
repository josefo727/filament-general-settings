<?php

if (! function_exists('getSetting')) {
    function getSetting(string $key, mixed $default = null): mixed
    {
        return \Josefo727\FilamentGeneralSettings\Models\GeneralSetting::getValue($key, $default);
    }
}
