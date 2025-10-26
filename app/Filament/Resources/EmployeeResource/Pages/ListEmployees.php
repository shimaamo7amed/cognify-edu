<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;
    public function getHeader(): ?View
    {
        return view('filament.resources.employee.header');
    }

    protected function getHeaderActions(): array
    {
        return [
            // Your existing header actions
        ];
    }
}