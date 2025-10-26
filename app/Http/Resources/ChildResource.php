<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class ChildResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $reports = $this->whenLoaded('sentReports', function () use ($request) {
            $reports = $this->sentReports
                ->sortByDesc('created_at')
                ->values();

            // إنشاء pagination يدوي
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 2);
            $offset = ($page - 1) * $perPage;
            
            $paginatedReports = $reports->slice($offset, $perPage);
            $total = $reports->count();

            // إنشاء paginator
            $paginator = new LengthAwarePaginator(
                $paginatedReports,
                $total,
                $perPage,
                $page,
                ['path' => $request->url()]
            );

            return [
                'data' => $paginatedReports->map(function ($report) use ($request) {
                    return [
                        'doc'   => $report->doc ? asset('storage/' . $report->doc) : null,
                        'status' => $report->status,
                        'report_type' => $report->report_type,
                        'Generated_on' => $report->created_at->format('n/j/Y'),
                        'observer_name' => $report->employee
                            ? trim($report->employee->name . ' ' .
                                $report->employee->middle_name . ' ' .
                                $report->employee->last_name)
                            : null,
                        'observation_session_name' => $report->child?->observationReports?->observationCase?->session
                            ? ($report->child?->observationReports?->observationCase?->session?->name[$request->header('Accept-Language', 'en')]
                                ?? $report->child?->observationReports?->observationCase?->session?->name['en'])
                            : null,
                    ];
                })->values(), // 🔹 تحويل لـ Array مرقمة
                'pagination' => [
                    'current_page'   => $paginator->currentPage(),
                    'from'           => $paginator->firstItem(),
                    'to'             => $paginator->lastItem(),
                    'per_page'       => $paginator->perPage(),
                    'last_page'      => $paginator->lastPage(),
                    'total'          => $paginator->total(),
                    'first_page_url' => $paginator->url(1),
                    'last_page_url'  => $paginator->url($paginator->lastPage()),
                    'next_page_url'  => $paginator->nextPageUrl(),
                    'prev_page_url'  => $paginator->previousPageUrl(),
                    'path'           => $paginator->path(),
                ]
            ];
        });

        return [
            'id'                     => $this->id,
            'childPhoto'             => $this->childPhoto,
            'fullName'               => $this->fullName,
            'age'                    => $this->age,
            'gender'                 => $this->gender,
            'schoolName'             => $this->schoolName,
            'schoolAddress'          => $this->schoolAddress,
            'homeAddress'            => $this->homeAddress,
            'textDescription'        => $this->textDescription,
            'voiceRecording'         => $this->voiceRecording,
            'foodAllergies'          => $this->foodAllergies,
            'environmentalAllergies' => $this->environmentalAllergies,
            'severityLevels'         => $this->severityLevels,
            'medicationAllergies'    => $this->medicationAllergies,
            'medicalConditions'      => $this->medicalConditions,
            'parent_id'              => $this->parent_id,
            'priority'               => $this->priority,
            'reports'                => $reports,
        ];
    }
}
