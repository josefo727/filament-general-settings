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

    /**
     * Cast a value for use according to its type.
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    public function castForUse($value, $type)
    {
        return in_array($type, array_keys($this->types))
            ? $this->types[$type]['prepareForUse']($value)
            : $value;
    }

    /**
     * Get validation rule for a type.
     *
     * @param string $type
     * @return string
     */
    public function getValidationRule($type)
    {
        return in_array($type, array_keys($this->types))
            ? $this->types[$type]['rules']
            : '';
    }

    /**
     * Get types for select.
     *
     * @return array
     */
    public function getTypesForSelect(): array
    {
        return collect($this->types)->mapWithKeys(function($type, $key) {
            return [
                $key => $type['name']
            ];
        })->toArray();
    }

    /**
     * Get type info.
     *
     * @param string $type
     * @return array
     */
    public function getTypeInfo($type)
    {
        return $this->types[$type];
    }

    /**
     * Get list of types as comma-separated string.
     *
     * @return string
     */
    public function getListTypes()
    {
        return implode(',', array_keys($this->types));
    }

    /**
     * Get all types.
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Set types.
     *
     * @return void
     */
    public function setTypes()
    {
        $this->types = [
            'string' => [
                'name' => __('filament-general-settings::types.string'),
                'rules' => 'required|string',
                'prepareForUse' => fn($value) => (string) $value,
            ],
            'integer' => [
                'name' => __('filament-general-settings::types.integer'),
                'rules' => 'required|integer',
                'prepareForUse' => fn($value) => (int) $value
            ],
            'float' => [
                'name' => __('filament-general-settings::types.float'),
                'rules' => 'required|numeric',
                'prepareForUse' => fn($value) => (float) $value
            ],
            'boolean' => [
                'name' => __('filament-general-settings::types.boolean'),
                'rules' => 'required|string|in:1,true,on,yes,0,false,off,no',
                'prepareForUse' => function($value) {
                    $falseValues = ['0', 'false', 'off', 'no'];
                    return !in_array(strtolower((string) $value), $falseValues);
                }
            ],
            'array' => [
                'name' => __('filament-general-settings::types.array'),
                'rules' => 'required|string|regex:/^[^,]+(,[^,]+)*$/',
                'prepareForUse' => function($value) {
                        $value = preg_replace('/\s*,\s*/', ',', $value);
                        return explode(',', $value);
                    }
            ],
            'json' => [
                'name' => __('filament-general-settings::types.json'),
                'rules' => 'required|json',
                'prepareForUse' => fn($value) => json_decode($value, true)
            ],
            'date' => [
                'name' => __('filament-general-settings::types.date'),
                'rules' => 'required|date',
                'prepareForUse' => fn($value) => Carbon::parse($value)
            ],
            'time' => [
                'name' => __('filament-general-settings::types.time'),
                'rules' => 'required|date_format:H:i:s',
                'prepareForUse' => fn($value) => Carbon::parse($value)
            ],
            'datetime' => [
                'name' => __('filament-general-settings::types.datetime'),
                'rules' => 'required|date_format:Y-m-d H:i:s',
                'prepareForUse' => fn($value) => Carbon::parse($value)
            ],
            'url' => [
                'name' => __('filament-general-settings::types.url'),
                'rules' => 'required|url',
                'prepareForUse' => fn($value) => $value
            ],
            'email' => [
                'name' => __('filament-general-settings::types.email'),
                'rules' => 'required|email',
                'prepareForUse' => fn($value) => $value
            ],
            'emails' => [
                'name' => __('filament-general-settings::types.emails'),
                'rules' => 'required|string|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(,[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})*$/',
                'prepareForUse' => function($value) {
                        $value = preg_replace('/\s*,\s*/', ',', $value);
                        return explode(',', $value);
                    }
            ],
            'password' => [
                'name' => __('filament-general-settings::types.password'),
                'rules' => 'required|string|min:4',
                'prepareForUse' => function ($value) {
                        $isEncrypted = Config::get('filament-general-settings.encryption.enabled');
                        $encryption = new EncryptionService();
                        return $isEncrypted
                            ? $encryption->decrypt($value)
                            : $value;
                    }
            ]
        ];
    }
}
