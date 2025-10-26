<?php

namespace App\Filament\Resources\ObservationSessionResource\Pages;

use App\Filament\Resources\ObservationSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObservationSession extends EditRecord
{
    protected static string $resource = ObservationSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
