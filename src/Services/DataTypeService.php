<?php

namespace Josefo727\FilamentGeneralSettings\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class DataTypeService
{
    protected array $types = [];

    public function __construct()
    {
        $this->setTypes();
    }

    public function castForUse($value, $type)
    {
        return in_array($type, array_keys($this->types))
            ? $this->types[$type]['prepareForUse']($value)
            : $value;
    }

    public function getValidationRule($type)
    {
        return in_array($type, array_keys($this->types))
            ? $this->types[$type]['rules']
            : '';
    }

    public function getTypesForSelect(): array
    {
        return collect($this->types)->mapWithKeys(function ($type, $key) {
            return [
                $key => $type['name'],
            ];
        })->toArray();
    }

    public function getTypeInfo($type)
    {
        return $this->types[$type];
    }

    public function getListTypes()
    {
        return implode(',', array_keys($this->types));
    }

    public function getTypes()
    {
        return $this->types;
    }

    public function setTypes()
    {
        $this->types = [
            'string' => [
                'name' => __('filament-general-settings::types.string'),
                'rules' => 'required|string',
                'prepareForUse' => fn ($value) => (string) $value,
            ],
            'integer' => [
                'name' => __('filament-general-settings::types.integer'),
                'rules' => 'required|integer',
                'prepareForUse' => fn ($value) => (int) $value,
            ],
            'float' => [
                'name' => __('filament-general-settings::types.float'),
                'rules' => 'required|numeric',
                'prepareForUse' => fn ($value) => (float) $value,
            ],
            'boolean' => [
                'name' => __('filament-general-settings::types.boolean'),
                'rules' => 'required|string|in:1,true,on,yes,0,false,off,no',
                'prepareForUse' => function ($value) {
                    if (is_bool($value)) {
                        return $value;
                    }

                    if (is_string($value)) {
                        $value = strtolower($value);

                        // Valores que deben convertirse a true
                        if (in_array($value, ['1', 'true', 'on', 'yes'])) {
                            return true;
                        }

                        // Valores que deben convertirse a false
                        if (in_array($value, ['0', 'false', 'off', 'no'])) {
                            return false;
                        }
                    }

                    // Conversión estándar de PHP para otros casos
                    return (bool) $value;
                },
            ],
            'array' => [
                'name' => __('filament-general-settings::types.array'),
                'rules' => 'required|string|regex:/^[^,]+(,[^,]+)*$/',
                'prepareForUse' => function ($value) {
                    if (is_array($value)) {
                        return $value;
                    }

                    if (empty($value)) {
                        return [];
                    }

                    $value = preg_replace('/\s*,\s*/', ',', $value);

                    return array_map('trim', explode(',', $value));
                },
            ],
            'json' => [
                'name' => __('filament-general-settings::types.json'),
                'rules' => 'required|json',
                'prepareForUse' => fn ($value) => json_decode($value, true),
            ],
            'date' => [
                'name' => __('filament-general-settings::types.date'),
                'rules' => 'required|date',
                'prepareForUse' => function ($value) {
                    if (empty($value)) {
                        return $value;
                    }

                    try {
                        return Carbon::parse($value);
                    } catch (\Exception $e) {
                        return $value;
                    }
                },
            ],
            'time' => [
                'name' => __('filament-general-settings::types.time'),
                'rules' => 'required|date_format:H:i:s',
                'prepareForUse' => function ($value) {
                    if (empty($value)) {
                        return $value;
                    }

                    try {
                        return Carbon::parse($value);
                    } catch (\Exception $e) {
                        return $value;
                    }
                },
            ],
            'datetime' => [
                'name' => __('filament-general-settings::types.datetime'),
                'rules' => 'required|date_format:Y-m-d H:i:s',
                'prepareForUse' => function ($value) {
                    if (empty($value)) {
                        return $value;
                    }

                    try {
                        return Carbon::parse($value);
                    } catch (\Exception $e) {
                        return $value;
                    }
                },
            ],
            'url' => [
                'name' => __('filament-general-settings::types.url'),
                'rules' => 'required|url',
                'prepareForUse' => fn ($value) => $value,
            ],
            'email' => [
                'name' => __('filament-general-settings::types.email'),
                'rules' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'prepareForUse' => fn ($value) => $value,
            ],
            'emails' => [
                'name' => __('filament-general-settings::types.emails'),
                'rules' => 'required|string|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(,[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})*$/',
                'prepareForUse' => function ($value) {
                    if (is_array($value)) {
                        return $value;
                    }

                    if (empty($value)) {
                        return [];
                    }

                    $value = preg_replace('/\s*,\s*/', ',', $value);

                    return array_map('trim', explode(',', $value));
                },
            ],
            'password' => [
                'name' => __('filament-general-settings::types.password'),
                'rules' => 'required|string|min:4',
                'prepareForUse' => function ($value) {
                    if (Config::get('filament-general-settings.encryption.enabled')) {
                        $encryption = new EncryptionService;

                        return $encryption->decrypt($value);
                    }

                    return $value;
                },
            ],
        ];
    }

    public function castForStorage($value, string $type)
    {
        if (is_null($value)) {
            return null;
        }

        return match ($type) {
            'string' => (string) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'boolean' => (bool) $value,
            'array' => is_array($value) ? implode(',', $value) : $value,
            'json' => is_array($value) ? json_encode($value) : $value,
            'date' => $value,
            'time' => $value,
            'datetime' => $value,
            'url' => $value,
            'email' => $value,
            'emails' => is_array($value) ? implode(',', $value) : $value,
            'password' => $value, // La encriptación se maneja en el modelo
            default => $value,
        };
    }
}
