<?php

namespace Josefo727\FilamentGeneralSettings\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Pages;
use Josefo727\FilamentGeneralSettings\Models\GeneralSetting;
use Josefo727\FilamentGeneralSettings\Services\DataTypeService;

class GeneralSettingResource extends Resource
{
    protected static ?string $model = GeneralSetting::class;

    public static function getModelLabel(): string
    {
        return __('filament-general-settings::general.label_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-general-settings::general.label_plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-general-settings::general.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return config('filament-general-settings.navigation.group', 'Settings');
    }

    public static function getNavigationIcon(): string
    {
        return config('filament-general-settings.navigation.icon', 'heroicon-o-cog');
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-general-settings.navigation.sort', 1);
    }

    public static function form(Schema $schema): Schema
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
                    ->afterStateUpdated(function (Forms\Set $set) {
                        // Clear value when type changes
                        $set('value', '');
                    }),

                Forms\Components\Textarea::make('description')
                    ->label(__('filament-general-settings::general.fields.description'))
                    ->maxLength(255)
                    ->columnSpanFull(),

                // Dynamic field for the value according to the selected type
                Forms\Components\Group::make()
                    ->schema(function (Forms\Get $get) use ($dataTypeService) {
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
                                Forms\Components\Toggle::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->formatStateUsing(function ($state) {
                                        if ($state === null) {
                                            return false;
                                        }
                                        if (is_string($state)) {
                                            return in_array(strtolower($state), ['1', 'true', 'on', 'yes']);
                                        }

                                        return (bool) $state;
                                    }),
                            ],
                            'array' => [
                                Forms\Components\Textarea::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                                        if (! $state) {
                                            return;
                                        }
                                        $set('value', preg_replace('/\s*,\s*/', ',', trim($state)));
                                    })
                                    ->helperText(new HtmlString('<span class="text-xs text-gray-500">value 01, value 02</span>')),
                            ],
                            'json' => [
                                Forms\Components\Textarea::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->rules($rulesArray)
                                    ->helperText(new HtmlString('<span class="text-xs text-gray-500">{"clave": "valor"}</span>')),
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
                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
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
                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
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

    public static function table(Table $table): Table
    {
        $dataTypeService = new DataTypeService;

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-general-settings::general.fields.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament-general-settings::general.fields.type'))
                    ->formatStateUsing(fn ($state) => $dataTypeService->getTypes()[$state]['name'] ?? $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('valueForDisplay')
                    ->label(__('filament-general-settings::general.fields.value'))
                    ->formatStateUsing(function ($state) {
                        // Ensuring that the value is a chain
                        if (is_array($state)) {
                            return json_encode($state);
                        }

                        return (string) $state;
                    })
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('filament-general-settings::general.fields.description'))
                    ->searchable()
                    ->limit(20),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeneralSettings::route('/'),
            'create' => Pages\CreateGeneralSetting::route('/create'),
            'edit' => Pages\EditGeneralSetting::route('/{record}/edit'),
        ];
    }
}
