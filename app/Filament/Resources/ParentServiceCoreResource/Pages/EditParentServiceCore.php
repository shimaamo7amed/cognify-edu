<?php

namespace App\Filament\Resources\ParentServiceCoreResource\Pages;

use App\Filament\Resources\ParentServiceCoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParentServiceCore extends EditRecord
{
    protected static string $resource = ParentServiceCoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
