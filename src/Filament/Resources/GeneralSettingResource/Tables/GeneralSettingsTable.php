<?php

namespace Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Josefo727\FilamentGeneralSettings\Services\DataTypeService;

class GeneralSettingsTable
{
    public static function configure(Table $table): Table
    {
        $dataTypeService = new DataTypeService;

        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-general-settings::general.fields.name'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('filament-general-settings::general.fields.type'))
                    ->formatStateUsing(fn ($state) => $dataTypeService->getTypes()[$state]['name'] ?? $state)
                    ->searchable(),
                TextColumn::make('valueForDisplay')
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
                TextColumn::make('description')
                    ->label(__('filament-general-settings::general.fields.description'))
                    ->searchable()
                    ->limit(20),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
