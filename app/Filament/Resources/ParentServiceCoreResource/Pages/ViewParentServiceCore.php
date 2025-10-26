<?php

namespace App\Filament\Resources\ParentServiceCoreResource\Pages;

use App\Filament\Resources\ParentServiceCoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewParentServiceCore extends ViewRecord
{
    protected static string $resource = ParentServiceCoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
