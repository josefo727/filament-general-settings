<?php

namespace Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Schemas;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Josefo727\FilamentGeneralSettings\Services\DataTypeService;

class GeneralSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        $dataTypeService = new DataTypeService;

        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->label(__('filament-general-settings::general.fields.name'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label(__('filament-general-settings::general.fields.type'))
                    ->options($dataTypeService->getTypesForSelect())
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Set $set) {
                        // Clear value when type changes
                        $set('value', '');
                    }),

                Forms\Components\Textarea::make('description')
                    ->label(__('filament-general-settings::general.fields.description'))
                    ->maxLength(255)
                    ->columnSpanFull(),

                // Dynamic field for the value according to the selected type
                FusedGroup::make()
                    ->schema(function (Get $get) use ($dataTypeService) {
                        $type = $get('type');

                        if (empty($type)) {
                            return [
                                Forms\Components\Textarea::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->required(),
                            ];
                        }

                        // Get validation rules from DataTypeService
                        $validationRules = $dataTypeService->getValidationRule($type);

                        // Parse the validation rules to extract individual rules
                        $rulesArray = $validationRules ?? 'required';

                        return match ($type) {
                            'string' => [
                                Forms\Components\Textarea::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->helperText('Any plain text'),
                            ],
                            'integer' => [
                                Forms\Components\TextInput::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->numeric()
                                    ->integer()
                                    ->rules($rulesArray)
                                    ->helperText('35'),
                            ],
                            'float' => [
                                Forms\Components\TextInput::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->numeric()
                                    ->rules($rulesArray)
                                    ->helperText('35.25'),
                            ],
                            'boolean' => [
                                Forms\Components\ToggleButtons::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->options([
                                        '1' => 'True',
                                        '0' => 'False',
                                    ])
                                    ->default('0')
                                    ->inline()
                                    ->required()
                                    ->helperText('Select True or False'),
                            ],
                            'array' => [
                                Forms\Components\Textarea::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        if (! $state) {
                                            return;
                                        }
                                        $set('value', preg_replace('/\s*,\s*/', ',', trim($state)));
                                    })
                                    ->helperText(new HtmlString('<span class="text-xs text-gray-500">value 01, value 02</span>'))
                                    ->rows(10),
                            ],
                            'json' => [
                                Forms\Components\Textarea::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->helperText(new HtmlString('<span class="text-xs text-gray-500">{&quot;clave&quot;: &quot;valor&quot;}</span>'))
                                    ->rows(10),
                            ],
                            'date' => [
                                Forms\Components\DatePicker::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->helperText('06/07/2025'),
                            ],
                            'time' => [
                                Forms\Components\TimePicker::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->seconds()
                                    ->helperText('10:25:00 am'),
                            ],
                            'datetime' => [
                                Forms\Components\DateTimePicker::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->seconds()
                                    ->helperText('06/07/2025 10:25:00 am')
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        if (! $state) {
                                            return;
                                        }
                                        $set('value', Carbon::parse($state)->format('Y-m-d H:i:s'));
                                    })
                                    ->dehydrateStateUsing(fn ($state) => $state ? Carbon::parse($state)->format('Y-m-d H:i:s') : null),
                            ],
                            'url' => [
                                Forms\Components\TextInput::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->url()
                                    ->helperText('https://example.com/path/to/resource?query=string#hash'),
                            ],
                            'email' => [
                                Forms\Components\TextInput::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->email()
                                    ->helperText('name@mail.com'),
                            ],
                            'emails' => [
                                Forms\Components\Textarea::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->placeholder(__('filament-general-settings::types.emails'))
                                    ->rules($rulesArray)
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        if (! $state) {
                                            return;
                                        }
                                        $set('value', preg_replace('/\s*,\s*/', ',', trim($state)));
                                    })
                                    ->dehydrateStateUsing(fn ($state) => $state ? preg_replace('/\s*,\s*/', ',', $state) : null)
                                    ->helperText('name_1@mail.com,name_2@mail.com,name_3@mail.com'),
                            ],
                            'password' => [
                                Forms\Components\TextInput::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->password()
                                    ->afterStateHydrated(function ($set, $get, $state, $record) {
                                        if ($record && $get('type') === 'password') {
                                            $set('value', null);
                                        }
                                    })
                                    ->revealable()
                                    ->helperText('Z&5G5WvTvrIviJ'),
                            ],
                            default => [
                                Forms\Components\Textarea::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray),
                            ],
                        };
                    })
                    ->columnSpanFull(),
            ]);
    }
}
