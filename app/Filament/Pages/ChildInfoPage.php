<?php
namespace App\Filament\Pages;

use Livewire\Component;
use App\Models\Employee;


use Filament\Pages\Page;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use App\Traits\HasObservationNotes;
use App\Models\ObservationChildCase;
use App\Traits\HandlesStatusUpdatedBy;

class ChildInfoPage extends Page
{
    use HandlesStatusUpdatedBy;

    use HasObservationNotes;


    
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.child-info-page';
    protected static ?string $slug = 'request/{record}/child-info';

    public ?ObservationChildCase $requestCase = null;
    public ?string $status = null;
    public ?int $employee_id = null;
    public string $noteText = '';
    public $notes;
    
    public function mount($record)
    {
        $this->requestCase = ObservationChildCase::with('child.parent')->findOrFail($record);
        $this->status = $this->requestCase->status;
        $this->employee_id = $this->requestCase->employee_id;
        $this->loadNotes();

    }

    public function getAcceptedEmployeesProperty()
    {
        return Employee::where('is_approved', 'accepted')->get();
    }

    public function updateStatus()
    {
        $this->validate([
            'status' => 'required|string'
        ]);

        $this->requestCase->status = $this->status;
        $this->fillStatusUpdatedBy($this->requestCase);
        $this->requestCase->save();
        $this->requestCase->refresh();

        session()->flash('message', 'Status updated successfully.');
    }


    public function assignTeacher()
    {
        $this->validate([
            'employee_id' => 'required|exists:employees,id'
        ]);

        $this->requestCase->update([
            'employee_id' => $this->employee_id,
        ]);
        session()->flash('message', 'Teacher assigned successfully.');
    }

    
}

