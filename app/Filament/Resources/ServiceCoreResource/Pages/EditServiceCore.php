<?php

namespace App\Filament\Resources\ServiceCoreResource\Pages;

use App\Filament\Resources\ServiceCoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceCore extends EditRecord
{
    protected static string $resource = ServiceCoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
