<?php

use App\Models\DailyReport;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ObservationReport;
use Illuminate\Support\Facades\App;
use App\Models\ObservationChildCase;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FawryController;
use App\Filament\EmployeePanel\Pages\ViewChild;
use App\Http\Controllers\E_Commerce\OrderController;
use App\Http\Controllers\ObservationChildCaseController;

Route::get('change-language/{lang}', function ($lang) {
    if (in_array($lang, ['ar', 'en'])) {
        session()->put('locale', $lang);
        App::setLocale($lang);

        return redirect()->back();
    }

    return redirect()->back();
})->name('change-language');


//Download observation report

Route::get('/admin/reports/{id}/download', function ($id) {
    $report = ObservationReport::findOrFail($id);
    $reportData = ReportService::generateFullReportData($report);

    $pdf = Pdf::loadView('pdf.report', [
        'data' => $reportData['data'],
        'child' => $reportData['child'],
    ]);

    return $pdf->download("report-{$report->id}.pdf");
})->name('report.download');
//Download daily report

Route::get('/admin/daily-reports/{id}/download', function ($id) {
    $report = DailyReport::with(['child', 'employee'])->findOrFail($id);

    $pdf = Pdf::loadView('pdf.daily-report', [
        'report' => $report,
    ]);

    return $pdf->download("daily-report-{$report->id}.pdf");
})->name('daily-report.download');



Route::get('employee/view-child/{childId}', ViewChild::class)
    ->name('filament.employeePanel.pages.view-child');





// Payment routes
Route::get('/payments/verify/{payment?}', [ObservationChildCaseController::class, 'payment_verify'])->name('verify-payment');
Route::get('/payments/fawry', [ObservationChildCaseController::class, 'showFawryPaymentPage'])->name('fawry.payment.page');
Route::get('/payments/fawry', [OrderController::class, 'showFawryPaymentPage'])->name('fawry.payment.page');

Route::get('/payments/verify/{payment?}', [OrderController::class, 'verifyFawryPayment'])->name('verify-payment');
