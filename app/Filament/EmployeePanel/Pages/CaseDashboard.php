<?php
// App\Filament\EmployeePanel\Pages\CaseDashboard.php

namespace App\Filament\EmployeePanel\Pages;

use Filament\Pages\Page;
use App\Models\ObservationChildCase;
use Illuminate\Support\Facades\Auth;

class CaseDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static string $view = 'filament.employee-panel.pages.case-dashboard';

    public $cases;
    public $stats;
public static function canAccess(): bool
{
    return auth('employee')->check();
}

    public function mount(): void
    {
        $employeeId = Auth::guard('employee')->id();

        $this->cases = ObservationChildCase::with(['child'])
            ->where('employee_id', $employeeId)
            ->latest()
            ->get();

        $this->stats = [
            'active' => $this->cases->count(),
            'completed' => $this->cases->where('status', 'completed')->count(),
            'pending_reports' => $this->cases->where('status', 'scheduled')->count(),
            'rating' => 4.9,
        ];
    }
}
