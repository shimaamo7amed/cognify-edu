<?php


namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\ObservationChildCase;


class DocumentsPage extends Page
{
    


    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.documents-page';

    protected static ?string $slug = 'request/{record}/documents';

    public ?ObservationChildCase $requestCase = null;

    public function mount($record)
    {
        $this->requestCase = ObservationChildCase::with('child.parent')->findOrFail($record);
    }
}

