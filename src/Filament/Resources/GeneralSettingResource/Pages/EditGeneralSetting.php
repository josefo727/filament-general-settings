<?php

namespace Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource;

class EditGeneralSetting extends EditRecord
{
    protected static string $resource = GeneralSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label(__('filament-general-settings::general.delete_button'))
                ->successNotificationTitle(__('filament-general-settings::general.success_deleted')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('filament-general-settings::general.success_updated');
    }
}
