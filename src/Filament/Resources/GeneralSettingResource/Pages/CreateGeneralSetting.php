<?php

namespace Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Pages;

use Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGeneralSetting extends CreateRecord
{
    protected static string $resource = GeneralSettingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('filament-general-settings::general.success_created');
    }
}