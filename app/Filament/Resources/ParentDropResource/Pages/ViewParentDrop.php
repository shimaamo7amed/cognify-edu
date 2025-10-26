<?php

namespace App\Filament\Resources\ParentDropResource\Pages;

use App\Filament\Resources\ParentDropResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewParentDrop extends ViewRecord
{
    protected static string $resource = ParentDropResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
