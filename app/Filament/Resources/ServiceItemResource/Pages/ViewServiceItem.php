<?php

namespace App\Filament\Resources\ServiceItemResource\Pages;

use App\Filament\Resources\ServiceItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceItem extends ViewRecord
{
    protected static string $resource = ServiceItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
