<?php

namespace App\Filament\Resources\SessionSlotResource\Pages;

use App\Filament\Resources\SessionSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSessionSlot extends EditRecord
{
    protected static string $resource = SessionSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
