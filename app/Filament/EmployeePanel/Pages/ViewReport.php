<?php

namespace App\Filament\EmployeePanel\Pages;

use Filament\Pages\Page;
use App\Models\DailyReport;
use App\Models\ObservationReport;
use Illuminate\Http\Request;

class ViewReport extends Page
{
    protected static ?string $title = 'View Report';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.employee-panel.pages.view-report';

    public $report;
    public $type;

    public function mount(Request $request)
    {
        $this->type = $request->get('type');
        $id = $request->get('id');

        if ($this->type === 'observation') {
            $this->report = ObservationReport::with('child')->findOrFail($id);
        } else {
            $this->report = DailyReport::with('child')->findOrFail($id);
        }
    }
}
