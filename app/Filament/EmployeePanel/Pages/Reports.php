<?php

namespace App\Filament\EmployeePanel\Pages;

use Filament\Pages\Page;
use Illuminate\Http\Request;
use App\Models\DailyReport;
use App\Models\ObservationReport;
use Illuminate\Support\Facades\Auth;

class Reports extends Page
{
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $title = 'Reports Dashboard';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.employee-panel.pages.reports';

    public $observationReports;
    public $dailyReports;
    public $children;
    public $filter;
    public $childId;

    // public function mount(Request $request)
    // {
    //     $this->filter = $request->get('filter', 'all');
    //     $this->childId = $request->get('child_id');

    //     // Get employee's children
    //     $this->children = Auth::user()->assignedChildren()->pluck('fullName', 'cognify_children.id');

    //     // Observation Reports
    //     $this->observationReports = collect();
    //     if ($this->filter == 'all' || $this->filter == 'observation') {
    //         $this->observationReports = ObservationReport::with('child')
    //             ->when($this->childId, fn ($q) => $q->where('child_id', $this->childId))
    //             ->latest()
    //             ->get();
    //     }

    //     // Daily Reports
    //     $this->dailyReports = collect();
    //     if ($this->filter == 'all' || $this->filter == 'daily') {
    //         if ($this->childId) {
    //             // Show all reports for selected child
    //             $this->dailyReports = DailyReport::with('child')
    //                 ->where('child_id', $this->childId)
    //                 ->orderBy('report_date', 'desc')
    //                 ->get();
    //         } else {
    //             // Show latest report per child
    //             $lastReportsIds = DailyReport::selectRaw('MAX(id) as id')
    //                 ->groupBy('child_id')
    //                 ->pluck('id');

    //             $this->dailyReports = DailyReport::with('child')
    //                 ->whereIn('id', $lastReportsIds)
    //                 ->orderBy('report_date', 'desc')
    //                 ->get();
    //         }
    //     }
    // }
    public function mount(Request $request)
{
    $this->filter = $request->get('filter', 'all');
    $this->childId = $request->get('child_id');

    // Get employee's assigned children from pivot table
    $this->children = Auth::user()->assignedChildren()->pluck('fullName', 'cognify_children.id');

    // Get IDs of assigned children (for daily reports)
    $assignedChildIds = Auth::user()->assignedChildren()->pluck('cognify_children.id');
;

    // Get child IDs related to the employee in observation_child_case
    $observationChildIds = \App\Models\ObservationChildCase::where('employee_id', Auth::id())
        ->pluck('child_id')
        ->unique();

    // Observation Reports
    $this->observationReports = collect();
    if ($this->filter == 'all' || $this->filter == 'observation') {
        $this->observationReports = ObservationReport::with('child')
            ->whereIn('child_id', $observationChildIds)
            ->when($this->childId, fn ($q) => $q->where('child_id', $this->childId))
            ->latest()
            ->get();
    }

    // Daily Reports
    $this->dailyReports = collect();
    if ($this->filter == 'all' || $this->filter == 'daily') {
        if ($this->childId) {
            // فقط للأطفال المعينين لهذا الموظف
            if ($assignedChildIds->contains($this->childId)) {
                $this->dailyReports = DailyReport::with('child')
                    ->where('child_id', $this->childId)
                    ->orderBy('report_date', 'desc')
                    ->get();
            }
        } else {
            // أحدث تقرير لكل طفل معين للموظف
            $lastReportsIds = DailyReport::whereIn('child_id', $assignedChildIds)
                ->selectRaw('MAX(id) as id')
                ->groupBy('child_id')
                ->pluck('id');

            $this->dailyReports = DailyReport::with('child')
                ->whereIn('id', $lastReportsIds)
                ->orderBy('report_date', 'desc')
                ->get();
        }
    }
}

}
