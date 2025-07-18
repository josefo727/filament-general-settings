<?php

namespace Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Pages;

use Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListGeneralSettings extends ListRecords
{
    protected static string $resource = GeneralSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('filament-general-settings::general.create_button')),
        ];
    }
}