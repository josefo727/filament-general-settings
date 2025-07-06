<?php

namespace Josefo727\FilamentGeneralSettings\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Pages;
use Josefo727\FilamentGeneralSettings\Models\GeneralSetting;
use Josefo727\FilamentGeneralSettings\Services\DataTypeService;

class GeneralSettingResource extends Resource
{
    protected static ?string $model = GeneralSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function getNavigationLabel(): string
    {
        return __('filament-general-settings::general.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament-general-settings::general.navigation_group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-general-settings::general.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-general-settings::general.title');
    }

    public static function form(Form $form): Form
    {
        $dataTypeService = new DataTypeService();

        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament-general-settings::general.fields.name'))
                            ->required()
                            ->unique(
                                table: fn () => \Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider::getTableName(),
                                column: 'name',
                                ignorable: fn ($record) => $record
                            )
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label(__('filament-general-settings::general.fields.type'))
                            ->required()
                            ->reactive()
                            ->options($dataTypeService->getTypes())
                            ->afterStateUpdated(function (Set $set) {
                                $set('value', '');
                            }),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament-general-settings::general.fields.description'))
                            ->columnSpan('full')
                            ->rows(3)
                            ->maxLength(255),
                        Forms\Components\Textarea::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->required()
                            ->columnSpan('full')
                            ->rows(3)
                            // Gestionar la visualización de valores de array y otros tipos especiales
                            ->formatStateUsing(function ($state, $record) use ($dataTypeService) {
                                if (!$record) return $state;

                                if (in_array($record->type, ['array', 'emails']) && is_string($state)) {
                                    return $state; // Ya está en formato string, listo para editar
                                }

                                if ($record->type === 'password') {
                                    // Si estamos en edición, no mostramos la contraseña
                                    return '';
                                }

                                if ($record->type === 'json' && is_string($state)) {
                                    // Formatear JSON para mejor visualización
                                    $decoded = json_decode($state, true);
                                    if ($decoded && json_last_error() === JSON_ERROR_NONE) {
                                        return json_encode($decoded, JSON_PRETTY_PRINT);
                                    }
                                }

                                return $state;
                            })
                            // Transformar datos antes de guardar
                            ->dehydrateStateUsing(function ($state, Get $get) use ($dataTypeService) {
                                $type = $get('type');
                                return $dataTypeService->castForStorage($state, $type);
                            }),
                    ]),
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
                    ->formatStateUsing(fn ($state) => $dataTypeService->getTypes()[$state] ?? $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('valueForDisplay')
                    ->label(__('filament-general-settings::general.fields.value'))
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
