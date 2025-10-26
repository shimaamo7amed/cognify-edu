<?php

namespace App\Filament\Pages;

use App\Models\Report;
use Filament\Pages\Page;
use App\Models\DailyReport;
use App\Models\CognifyChild;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ObservationReport;
use App\Models\ObservationChildCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class ChildReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'Child Reports';
    protected static string $view = 'filament.pages.child-reports';
    protected static bool $shouldRegisterNavigation = false;

    public ?ObservationChildCase $requestCase = null;
    public $reports = [];
    public $dailyReports = [];
    public $children = [];
    public $childId;

    public function mount(Request $request, $record = null): void
    {
        $this->childId = $request->get('child_id');
    

            $this->children = CognifyChild::whereIn(
                'id',
                ObservationChildCase::pluck('child_id')
                )
                ->pluck('fullName', 'id');

        if ($record) {
            $this->requestCase = ObservationChildCase::with('child.parent')->findOrFail($record);

            $queryChildId = $this->childId ?? $this->requestCase->child_id;

            $this->reports = ObservationReport::with(['child', 'observationCase.employee'])
                ->where('child_id', $queryChildId)
                ->latest()
                ->get();

            $this->dailyReports = DailyReport::with('child')
                ->where('child_id', $queryChildId)
                ->latest()
                ->get();

        } else {
            $this->reports = ObservationReport::with(['child', 'observationCase.employee'])
                ->when($this->childId, fn ($q) => $q->where('child_id', $this->childId))
                ->latest()
                ->get();

            $this->dailyReports = DailyReport::with('child')
                ->when($this->childId, fn ($q) => $q->where('child_id', $this->childId))
                ->latest()
                ->get();
        }
    }
    public function sendToParent($reportId)
    {
        $report = DailyReport::with(['child.parent'])->findOrFail($reportId);
        if (! $report->child?->parent?->email) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'No parent email found for this child.'
            ]);
            return;
        }
        $pdf = Pdf::loadView('pdf.daily-report', ['report' => $report]);
        $filename = 'daily_report_' . $report->child->id . '_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        Storage::disk('public')->put('reports/' . $filename, $pdf->output());

        Report::create([
            'cognify_children_id' => $report->child_id,
            'doc' => 'reports/' . $filename,
            'status' => 'finalized',
            'report_type' => 'daily',
            'created_by' => auth('admin')->id() ?? auth('employee')->id(),
            'employee_id' => $report->employee_id,
        ]);
        Mail::send([], [], function ($message) use ($report, $pdf) {
            $message->to($report->child->parent->email)
                ->subject('Daily Report for ' . $report->child->fullName)
                ->attachData($pdf->output(), 'daily-report-'.$report->id.'.pdf');
        });
        ObservationReport::where('child_id', $report->child_id)->update(['delivery_status' => 'sent']);

        Notification::make()
            ->title('Report Sent ✅')
            ->body('The daily report has been sent to the parent successfully.')
            ->success()
            ->send();
    }


    public function download($reportId)
    {
        $report = DailyReport::with(['child', 'employee'])->findOrFail($reportId);

        $pdf = Pdf::loadView('pdf.daily-report', ['report' => $report]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "daily-report-{$report->id}.pdf"
        );
    }
}
