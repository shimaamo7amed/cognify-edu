<?php

namespace App\Filament\Resources\ObservationSessionResource\Pages;

use App\Filament\Resources\ObservationSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListObservationSessions extends ListRecords
{
    protected static string $resource = ObservationSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
