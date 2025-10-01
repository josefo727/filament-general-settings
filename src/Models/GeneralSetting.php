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
        'type',
    ];

    protected $appends = [
        'valueForDisplay',
    ];

    /**
     * Get the table name with prefix if configured.
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
     * @return array<string,mixed>
     */
    public static function getValidationRules(mixed $type = null, mixed $id = null): array
    {
        $dataType = new DataTypeService;
        $rules = $dataType->getValidationRule($type) ?: 'required';
        $tableName = FilamentGeneralSettingsServiceProvider::getTableName();

        return [
            'name' => 'required|string|unique:'.$tableName.',name,'.$id,
            'value' => $rules,
            'description' => 'nullable|string',
            'type' => 'required|string|in:'.$dataType->getListTypes(),
        ];
    }

    private static function prepareAttributesForSaving(array $attributes, ?int $id = null): array
    {
        if (isset($attributes['type']) && in_array($attributes['type'], ['emails', 'array']) && isset($attributes['value'])) {
            if (is_string($attributes['value'])) {
                $value = preg_replace('/\s+/', ' ', $attributes['value']);
                $attributes['value'] = preg_replace('/\s*,\s*/', ',', $value);
            } elseif (is_array($attributes['value'])) {
                $attributes['value'] = implode(',', $attributes['value']);
            }
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
     * @param  array<string,mixed>  $attributes
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
     * @param  array<string,mixed>  $attributes
     * @param  array<string,mixed>  $options
     */
    public static function updateSetting(GeneralSetting $setting, array $attributes = [], array $options = []): GeneralSetting
    {
        $attributes = self::prepareAttributesForSaving($attributes, $setting->id);

        $setting->fill($attributes)->save($options);

        return $setting;
    }

    /**
     * Set the value of the setting, encrypting if necessary.
     */
    public function setValue(): void
    {
        // Validate if the encryption configuration is enabled and if the type of value is password.
        if (Config::get('filament-general-settings.encryption.enabled') && $this->type === 'password') {
            // If so, we encrypt the value before saving it
            $encryption = new EncryptionService;
            $this->value = $encryption->encrypt($this->value);
        }
    }

    /**
     * Get the value for display.
     */
    public function getValueForDisplayAttribute(): string
    {
        if (is_null($this->value)) {
            return '';
        }

        if ($this->type === 'password') {
            if (! Config::get('filament-general-settings.show_passwords')) {
                return '********';
            }

            if (Config::get('filament-general-settings.encryption.enabled')) {
                $dataType = new DataTypeService;

                return $dataType->castForUse($this->value, 'password');
            }
        }

        if (in_array($this->type, ['array', 'emails'])) {
            if (is_array($this->value)) {
                return implode(', ', $this->value);
            }

            return $this->value;
        }

        if ($this->type === 'json') {
            $decoded = json_decode($this->value, true);
            if ($decoded && json_last_error() === JSON_ERROR_NONE) {
                return json_encode($decoded, JSON_PRETTY_PRINT);
            }
        }

        return (string) $this->value;
    }

    /**
     * @return mixed|string|null
     */
    public static function getValue(string $name, ?string $default = null): mixed
    {
        $setting = static::query()->firstWhere('name', '=', $name);

        if (is_null($setting)) {
            return $default;
        }

        $dataType = new DataTypeService;

        return $dataType->castForUse($setting->value, $setting->type);
    }

    public static function has(string $name): bool
    {
        return static::query()->where('name', '=', $name)->exists();
    }

    public static function remove(string $name): bool
    {
        $setting = static::query()->firstWhere('name', '=', $name);

        return (bool) optional($setting)->delete();
    }

    /**
     * Apply filters to the query.
     */
    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        return $query->when((bool) $request->name, function ($query) use ($request) {
            $query->where('name', 'LIKE', "%$request->name%");
        })
            ->when((bool) $request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when((bool) $request->value, function ($query) use ($request) {
                $query->where('value', 'LIKE', "%$request->value%");
            });
    }
}
