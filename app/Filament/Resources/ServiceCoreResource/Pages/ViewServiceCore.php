<?php

namespace App\Filament\Resources\ServiceCoreResource\Pages;

use App\Filament\Resources\ServiceCoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceCore extends ViewRecord
{
    protected static string $resource = ServiceCoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
