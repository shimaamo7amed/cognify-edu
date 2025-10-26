<?php

namespace App\Filament\Resources\SessionSlotResource\Pages;

use App\Filament\Resources\SessionSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSessionSlot extends ViewRecord
{
    protected static string $resource = SessionSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
