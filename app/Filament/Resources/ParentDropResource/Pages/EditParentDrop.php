<?php

namespace App\Filament\Resources\ParentDropResource\Pages;

use App\Filament\Resources\ParentDropResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParentDrop extends EditRecord
{
    protected static string $resource = ParentDropResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
