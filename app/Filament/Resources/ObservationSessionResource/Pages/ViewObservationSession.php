<?php

namespace App\Filament\Resources\ObservationSessionResource\Pages;

use App\Filament\Resources\ObservationSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewObservationSession extends ViewRecord
{
    protected static string $resource = ObservationSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
