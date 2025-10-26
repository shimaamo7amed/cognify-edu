<?php

namespace App\Filament\Resources\ServiceCoreResource\Pages;

use App\Filament\Resources\ServiceCoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceCores extends ListRecords
{
    protected static string $resource = ServiceCoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
