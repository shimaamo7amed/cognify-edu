<?php
namespace App\Filament\Pages;

use App\Models\Employee;
use Filament\Pages\Page;

use App\Models\CognifyChild;
use App\Traits\HasObservationNotes;
use App\Models\ObservationChildCase;
use Filament\Notifications\Notification;

class ViewRequest extends Page
{
    // use HandlesStatusUpdatedBy;
    use HasObservationNotes;

    public ?ObservationChildCase $requestCase = null;
    public ?string $status = null;
    public ?int $employee_id = null;

    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.view-request';
    protected static ?string $slug = 'view-request/{record}';
    protected static ?string $routeName = 'employee.view-request';


    public function mount($record): void
    {
        // dd(auth()->user()->hasRole('teacher'));
        $this->requestCase = ObservationChildCase::with('child.parent')->findOrFail($record);
        // dd($this->requestCase->employee_id);
        // dd(auth()->id());
        // dd([
//     'auth_id' => auth()->id(),
//     'employee_id' => $this->requestCase->employee_id,
//     'user_roles' => auth()->user()->roles->pluck('name'),
// ]);

        if (auth()->user()->hasRole('teacher')) {
            if ($this->requestCase->employee_id != auth()->id()) {
                abort(403, 'not authorized');
            }

        }
        $this->status = $this->requestCase->status;
        $this->employee_id = $this->requestCase->employee_id;
        $this->loadNotes();

    }

    public function getAllEmployeeChildrenProperty()
    {
        $employee = auth()->user();
        $childrenFromRelation = $employee->children;
        $childrenFromObservation = CognifyChild::whereHas('observationCases', function ($query) use ($employee) {
            $query->where('employee_id', $employee->id);
        })->get();
        $merged = $childrenFromRelation->merge($childrenFromObservation)->unique('id')->values();

        return $merged;
    }

    public function getAcceptedEmployeesProperty()
    {
        return Employee::where('is_approved', 'accepted')->get();
    }

    public function updatedEmployeeId($value)
    {
        $this->employee_id = $value;
    }

    public function assignTeacher()
    {
        // dd($this->employee_id);

        $this->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $this->requestCase->update([
            'employee_id' => $this->employee_id,
        ]);
        $this->requestCase->refresh();
        Notification::make()
            ->title('Teacher Assigned')
            ->success()
            ->send();
    }


    public function updateStatus()
    {
        $this->requestCase->update([
            'status' => $this->status,
            'status_updated_by' => auth()->user()->name
        ]);

        // session()->flash('message', 'Status updated successfully.');
        \Filament\Notifications\Notification::make()
            ->title('Status updated successfully.')
            ->success()
            ->send();
    }


}