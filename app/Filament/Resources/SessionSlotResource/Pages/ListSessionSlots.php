<?php

namespace App\Filament\Resources\SessionSlotResource\Pages;

use App\Filament\Resources\SessionSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSessionSlots extends ListRecords
{
    protected static string $resource = SessionSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
