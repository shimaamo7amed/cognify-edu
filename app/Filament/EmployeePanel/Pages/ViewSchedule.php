<?php

namespace App\Filament\EmployeePanel\Pages;

use Filament\Pages\Page;
use App\Models\ObservationChildCase;

class ViewSchedule extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.employee-panel.pages.view-schedule';
    protected static ?string $title = 'My Schedule';

    public $sessions;

    public function mount()
    {
        $this->sessions = ObservationChildCase::where('employee_id', auth()->id())
            ->where('slot_date', '>=', now())
            ->orderBy('slot_date', 'asc')
            ->get();
    }
}
