<?php

namespace Josefo727\FilamentGeneralSettings\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider;
use Josefo727\FilamentGeneralSettings\Services\DataTypeService;
use Josefo727\FilamentGeneralSettings\Services\EncryptionService;

/**
 * @method static \Illuminate\Database\Eloquent\Builder applyFilters(Request $request)
 */
class GeneralSetting extends Model
{
    protected $fillable = [
        'name',
        'value',
        'description',
        'type'
    ];

    protected $appends = [
        'valueForDisplay'
    ];

    /**
     * Get the table name with prefix if configured.
     *
     * @return string
     */
    public function getTable(): string
    {
        return FilamentGeneralSettingsServiceProvider::getTableName();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($setting) {
            $setting->setValue();
        });

        static::updating(function ($setting) {
            $setting->setValue();
        });
    }

    /**
     * Get validation rules for a setting.
     *
     * @param mixed $type
     * @param mixed $id
     * @return array<string,mixed>
     */
    public static function getValidationRules($type = null, $id = null): array
    {
        $dataType = new DataTypeService();
        $rules = $dataType->getValidationRule($type) ?: 'required';
        $tableName = FilamentGeneralSettingsServiceProvider::getTableName();

        return [
            'name' => 'required|string|unique:' . $tableName . ',name,' . $id,
            'value' => $rules,
            'description' => 'nullable|string',
            'type' => 'required|string|in:' . $dataType->getListTypes(),
        ];
    }

    /**
     * Create a new setting.
     *
     * @return GeneralSetting
     * @param array<string,mixed> $attributes
     */
    private static function prepareAttributesForSaving(array $attributes, ?int $id = null): array
    {
        if (isset($attributes['type']) && in_array($attributes['type'], ['emails', 'array']) && isset($attributes['value'])) {
            $value = preg_replace('/\s+/', ' ', $attributes['value']);
            $attributes['value'] = preg_replace('/\s*,\s*/', ',', $value);
        }
        
        // Validate data
        Validator::make(
            $attributes,
            self::getValidationRules($attributes['type'] ?? null, $id)
        )->validate();

        return $attributes;
    }

    /**
     * Create a new setting.
     *
     * @return GeneralSetting
     * @param array<string,mixed> $attributes
     */
    public static function create(array $attributes = []): GeneralSetting
    {
        $attributes = self::prepareAttributesForSaving($attributes);

        // Creates the object and saves the data
        $setting = new static($attributes);
        $setting->save();

        return $setting;
    }

    /**
     * Update a setting.
     *
     * @param GeneralSetting $setting
     * @param array<string,mixed> $attributes
     * @param array<string,mixed> $options
     * @return GeneralSetting
     */
    public static function updateSetting(GeneralSetting $setting, array $attributes = [], array $options = []): GeneralSetting
    {
        $attributes = self::prepareAttributesForSaving($attributes, $setting->id);

        $setting->fill($attributes)->save($options);

        return $setting;
    }

    /**
     * Set the value of the setting, encrypting if necessary.
     *
     * @return void
     */
    public function setValue(): void
    {
        // Validate if the encryption configuration is enabled and if the type of value is password.
        if (Config::get('filament-general-settings.encryption.enabled') && $this->type === 'password') {
            // If so, we encrypt the value before saving it
            $encryption = new EncryptionService();
            $this->value = $encryption->encrypt($this->value);
        }
    }

    /**
     * Get the value for display.
     *
     * @return string
     */
    public function getValueForDisplayAttribute(): string
    {
        if ($this->type !== 'password') {
            return $this->value;
        }

        if (!Config::get('filament-general-settings.show_passwords')) {
            return '';
        }

        if (Config::get('filament-general-settings.encryption.enabled')) {
            $dataType = new DataTypeService();
            return $dataType->castForUse($this->value, 'password');
        }

        return $this->value;
    }

    /**
     * Get a setting value by name.
     *
     * @param string $name
     * @return mixed
     */
    public static function getValue(string $name)
    {
        $setting = static::query()->firstWhere('name', '=', $name);

        if (is_null($setting)) {
            return null;
        }

        $dataType = new DataTypeService();

        return $dataType->castForUse($setting->value, $setting->type);
    }

    /**
     * Apply filters to the query.
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        return $query->when(!!$request->name, function ($query) use ($request) {
                $query->where('name', 'LIKE', "%$request->name%");
            })
            ->when(!!$request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when(!!$request->value, function ($query) use ($request) {
                $query->where('value', 'LIKE', "%$request->value%");
            });
    }
}