<?php


namespace App\Filament\Pages;

use App\Models\Employee;
use Filament\Pages\Page;
use App\Traits\HasObservationNotes;
use Illuminate\Contracts\View\View;
use App\Models\ObservationChildCase;

class TimelinePage extends Page
{
    use HasObservationNotes;

    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.timeline-page';
    protected static ?string $slug = 'request/{record}/timeline';

    public ?ObservationChildCase $requestCase = null;
    public $acceptedEmployees;

    public function mount($record)
    {
        $this->requestCase = ObservationChildCase::with(['child.parent', 'employee'])->findOrFail($record);
        $this->acceptedEmployees = Employee::where('is_approved', 'accepted')->get();
        $this->loadNotes();
    }

    public function getTitle(): string
    {
        return 'Request Timeline';
    }

    public function getHeader(): View
    {
        return view('filament.pages.partials.timeline-header');
    }
}
