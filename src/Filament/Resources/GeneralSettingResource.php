<?php

namespace Josefo727\FilamentGeneralSettings\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Pages;
use Josefo727\FilamentGeneralSettings\Models\GeneralSetting;
use Josefo727\FilamentGeneralSettings\Services\DataTypeService;
use Illuminate\Support\HtmlString;

class GeneralSettingResource extends Resource
{
    protected static ?string $model = GeneralSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

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

    public static function form(Form $form): Form
    {
        $dataTypeService = new DataTypeService();

        return $form
            ->schema([
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
                        // Limpiar el valor cuando cambia el tipo
                        $set('value', '');
                    }),

                // Campo dinámico para el valor según el tipo seleccionado
                Forms\Components\Group::make()
                    ->schema(function (Forms\Get $get) use ($dataTypeService) {
                        $type = $get('type');

                        if (empty($type)) {
                            return [
                                Forms\Components\Textarea::make('value')
                                    ->label(__('filament-general-settings::general.fields.value'))
                                    ->required()
                            ];
                        }

                        switch ($type) {
                            case 'string':
                                return [
                                    Forms\Components\TextInput::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->required()
                                ];
                            case 'integer':
                                return [
                                    Forms\Components\TextInput::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->numeric()
                                        ->integer()
                                        ->required()
                                ];
                            case 'float':
                                return [
                                    Forms\Components\TextInput::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->numeric()
                                        ->required()
                                ];
                            case 'boolean':
                                return [
                                    Forms\Components\Toggle::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->required()
                                        ->formatStateUsing(function ($state) {
                                            if ($state === null) {
                                                return false;
                                            }
                                            if (is_string($state)) {
                                                return in_array(strtolower($state), ['1', 'true', 'on', 'yes']);
                                            }
                                            return (bool) $state;
                                        })
                                ];
                            case 'array':
                                return [
                                    Forms\Components\TagsInput::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->required()
                                        ->separator(',')
                                ];
                            case 'json':
                                return [
                                    Forms\Components\Textarea::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->required()
                                        ->helperText(new HtmlString('<span class="text-xs text-gray-500">JSON válido (ej: {"clave": "valor"})</span>'))
                                        ->rule('json')
                                ];
                            case 'date':
                                return [
                                    Forms\Components\DatePicker::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->required()
                                ];
                            case 'time':
                                return [
                                    Forms\Components\TimePicker::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->required()
                                        ->seconds()
                                ];
                            case 'datetime':
                                return [
                                    Forms\Components\DateTimePicker::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->required()
                                        ->seconds()
                                ];
                            case 'url':
                                return [
                                    Forms\Components\TextInput::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->required()
                                        ->url()
                                ];
                            case 'email':
                                return [
                                    Forms\Components\TextInput::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->required()
                                        ->email()
                                ];
                            case 'emails':
                                return [
                                    Forms\Components\TagsInput::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->placeholder(__('filament-general-settings::types.emails'))
                                        ->required()
                                        ->separator(',')
                                        ->helperText(__('filament-general-settings::types.emails'))
                                ];
                            case 'password':
                                return [
                                    Forms\Components\TextInput::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->required()
                                        ->password()
                                        ->minLength(4)
                                        ->helperText(config('filament-general-settings.encryption.enabled')
                                            ? __('filament-general-settings::types.password')
                                            : __('filament-general-settings::types.password_not_encrypted'))
                                ];
                            default:
                                return [
                                    Forms\Components\Textarea::make('value')
                                        ->label(__('filament-general-settings::general.fields.value'))
                                        ->required()
                                ];
                        }
                    }),

                Forms\Components\Textarea::make('description')
                    ->label(__('filament-general-settings::general.fields.description'))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        $dataTypeService = new DataTypeService();

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
                        // Asegurarse de que el valor sea una cadena
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