<?php

namespace App\Filament\Resources\ParentDropResource\Pages;

use App\Filament\Resources\ParentDropResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParentDrops extends ListRecords
{
    protected static string $resource = ParentDropResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
