<?php
namespace App\Services;

use App\Models\ObservationReport;
use App\Models\ObservationChildCase;

class ReportService
{
    public static function generateFullReportData(ObservationReport $report): array
    {
    
        $requestCase = ObservationChildCase::with(['employee', 'session', 'child.parent'])
            ->findOrFail($report->observation_child_case_id);
        $data = $report->toArray();

        $data = array_merge($data, [
            'observer_name' => $requestCase->employee->name.' '.$requestCase->employee->middle_name.' '.$requestCase->employee->last_name ?? 'N/A',
            'observer_title' => $requestCase->session->name['en'] ?? 'N/A',
            'observation_date' => $requestCase->slot_date ?? 'N/A',
            'session_duration' => $requestCase->duration ?? 'N/A',
            'observation_location' => $requestCase->child->homeAddress ?? 'N/A',
            'required_support_level' => $requestCase->child->priority ?? 'N/A',
        ]);

        return [
            'data' => $data,
            'child' => $requestCase->child,
            'parent_email' => $requestCase->child->parent->email ?? null,
        ];
    }
}
