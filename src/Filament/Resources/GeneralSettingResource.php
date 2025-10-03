<?php

namespace Josefo727\FilamentGeneralSettings\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Pages;
use Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Schemas\GeneralSettingForm;
use Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Tables\GeneralSettingsTable;
use Josefo727\FilamentGeneralSettings\Models\GeneralSetting;

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
        return GeneralSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GeneralSettingsTable::configure($table);
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
