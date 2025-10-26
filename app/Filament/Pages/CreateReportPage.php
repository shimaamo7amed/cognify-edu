<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\Report;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ObservationReport;
use Illuminate\Support\Facades\DB;
use App\Models\ObservationChildCase;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Tabs;

class CreateReportPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Create Observation Report';
    protected static ?string $slug = 'request/{record}/create-report';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.create-report-page';

    public ?ObservationReport $observationReport = null;
    public ?ObservationChildCase $requestCase = null;
    public array $formData = [];
    public int $progress = 0;
    public ?Carbon $lastSavedAt = null;

    public static function getRouteName(?string $panel = null): string
    {
        return parent::getRouteName($panel);
    }

    public static function getSlug(): string
    {
        return 'request/{record}/create-report';
    }

    public function mount($record): void
    {
        $this->requestCase = ObservationChildCase::with(['child', 'employee', 'session'])->findOrFail($record);

        $this->observationReport = ObservationReport::where('observation_child_case_id', $this->requestCase->id)->first();

        $observerFullName = $this->requestCase->employee
            ? $this->requestCase->employee->name . ' ' . $this->requestCase->employee->middle_name . ' ' . $this->requestCase->employee->last_name
            : '';

        $this->formData = [
            'observation_date' => $this->requestCase->slot_date
                ? Carbon::parse($this->requestCase->slot_date)->format('Y-m-d')
                : null,
            'observer_name' => $observerFullName,
            'observer_title' => $this->requestCase->session->name['en'] ?? '',
            'session_duration' => $this->requestCase->duration ?? $this->requestCase->session->duration ?? '',
            'observation_location' => $this->requestCase->child->homeAddress ?? '',
            'required_support_level' => $this->requestCase->child->priority ?? '',
        ];

        $existingReport = ObservationReport::where('observation_child_case_id', $this->requestCase->id)->first();

        if ($existingReport) {
            $this->formData = array_merge($this->formData, $existingReport->toArray());

            if (isset($existingReport->recommended_follow_up_date)) {
                $this->formData['recommended_follow_up_date'] = Carbon::parse($existingReport->recommended_follow_up_date)->format('Y-m-d');
            }

            $this->lastSavedAt = $existingReport->updated_at;
        }

        $this->progress = $this->calculateProgress();
    }

    public function updated($name, $value)
    {
        if (str_starts_with($name, 'formData.')) {
            $this->progress = $this->calculateProgress();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('formData')
            ->model(ObservationReport::class);
    }

    public function saveDraft()
    {
        $data = $this->formData;

        if (!empty($data['recommended_follow_up_date'])) {
            $data['recommended_follow_up_date'] = Carbon::parse($data['recommended_follow_up_date']);
        }

        $this->requestCase->update([
            'slot_date' => $data['observation_date'],
            'duration' => $data['session_duration'],
        ]);

        $this->requestCase->child->update([
            'priority' => $data['required_support_level'] ?: null,
        ]);

        ObservationReport::updateOrCreate(
            ['observation_child_case_id' => $this->requestCase->id],
            array_merge($data, [
                'child_id' => $this->requestCase->child_id,
                'created_by' => auth('admin')->id(),
                'progress' => $this->calculateProgress(),
                'status' => 'draft',
            ])
        );

        $this->lastSavedAt = now();
        $this->progress = $this->calculateProgress();

        \Filament\Notifications\Notification::make()
            ->title('Draft saved successfully.')
            ->success()
            ->send();
    }

    public function finalizeReport()
    {
        if ($this->calculateProgress() < 100) {
            session()->flash('error', 'Complete all required fields to finalize.');
            return;
        }

        $data = $this->formData;

        if (!empty($data['recommended_follow_up_date'])) {
            $data['recommended_follow_up_date'] = Carbon::parse($data['recommended_follow_up_date']);
        }

        $this->requestCase->update([
            'slot_date' => $data['observation_date'],
            'duration' => $data['session_duration'],
            'status' => 'completed',
        ]);

        $this->requestCase->child->update([
            'priority' => $data['required_support_level'],
        ]);

        ObservationReport::updateOrCreate(
            ['observation_child_case_id' => $this->requestCase->id],
            array_merge($data, [
                'child_id' => $this->requestCase->child_id,
                'created_by' => auth('admin')->id(),
                'progress' => 100,
                'status' => 'finalized',
            ])
        );

        $this->sendReportFinalizedNotification();

        $this->formData['status'] = 'finalized';
        $this->lastSavedAt = now();
        $this->progress = 100;

        \Filament\Notifications\Notification::make()
            ->title('Report finalized successfully.')
            ->success()
            ->send();
    }

    public function sendToParent()
    {
        $data = $this->formData;

        $pdf = Pdf::loadView('pdf.report', [
            'data' => $data,
            'child' => $this->requestCase->child,
        ]);

        $pdfContent = $pdf->output();
        $filename = 'report_' . $this->requestCase->child->id . '_' . now()->format('Y_m_d_H_i_s') . '.pdf';

        Storage::disk('public')->put('reports/' . $filename, $pdfContent);

        ObservationReport::updateOrCreate(
            ['observation_child_case_id' => $this->requestCase->id],
            array_merge($data, [
                'child_id' => $this->requestCase->child_id,
                'created_by' => auth('admin')->id(),
                'progress' => 100,
                'status' => 'finalized',
                'delivery_status' => 'sent',
                'sent_at' => now(),
                'sent_by' => auth('admin')->id() ?? auth('employee')->id() ?? null,
            ])
        );

        $report = Report::create([
            'cognify_children_id' => $this->requestCase->child->id,
            'doc' => 'reports/' . $filename,
            'status' => 'finalized',
            'created_by' => auth('admin')->id(),
            'employee_id' => $this->requestCase->employee?->id,
        ]);

        $report->sendReportSentNotification();

        Mail::to($this->requestCase->child->parent->email ?? 'parent@example.com')
            ->send(new \App\Mail\ReportEmail($data, $this->requestCase->child, $pdfContent));

        \Filament\Notifications\Notification::make()
            ->title('Report sent to parent successfully.')
            ->success()
            ->send();
    }

    private function sendReportFinalizedNotification()
    {
        $recipients = \App\Models\Admin::all();

        $childName = $this->requestCase->child->name ?? 'Unknown Child';
        $currentUser = auth('admin')->user();

        foreach ($recipients as $recipient) {
            if ($recipient->id === $currentUser->id) {
                continue;
            }

            \Filament\Notifications\Notification::make()
                ->title('Observation Report Finalized')
                ->body("An observation report for {$childName} has been finalized by {$currentUser->name}")
                ->icon('heroicon-o-document-check')
                ->iconColor('success')
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->button()
                        ->label('View Case')
                        ->url("/admin/request/{$this->requestCase->id}/create-report")
                        ->markAsRead(),
                ])
                ->sendToDatabase($recipient);
        }
    }

    public function calculateProgress(): int
    {
        $fields = $this->extractFields($this->getFormSchema());
        $totalFields = count($fields) ?: 1;
        $filledFields = collect($fields)
            ->map(fn($field) => $field->getName())
            ->filter(fn($key) =>
                array_key_exists($key, $this->formData)
                && $this->formData[$key] !== null
                && $this->formData[$key] !== ''
            )
            ->count();

        return intval(($filledFields / $totalFields) * 100);
    }

    protected function extractFields(array $components): array
    {
        $fields = [];

        foreach ($components as $component) {
            if (method_exists($component, 'getChildComponents')) {
                $fields = array_merge($fields, $this->extractFields($component->getChildComponents()));
            }

            if ($component instanceof \Filament\Forms\Components\Field) {
                $fields[] = $component;
            }
        }

        return $fields;
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Report')->tabs([
                Tabs\Tab::make('Basic Info')->schema([
                    Forms\Components\DatePicker::make('observation_date')->required()->live(),
                    Forms\Components\TextInput::make('observer_name')
                        ->label('Observer Name')
                        ->readonly()
                        ->required()
                        ->live(),
                    Forms\Components\TextInput::make('observer_title')->live(),
                    Forms\Components\TextInput::make('session_duration')->live(),
                    Forms\Components\TextInput::make('observation_location')->live(),
                    Forms\Components\Select::make('required_support_level')
                        ->label('Required Support Level ')
                        ->options([
                            'high' => 'High Support',
                            'medium' => 'Medium Support',
                            'low' => 'Low Support',
                            'Intensive' => 'Intensive Support',
                        ])
                        ->live(),
                ]),
                Tabs\Tab::make('Observation')->schema([
                    Forms\Components\Textarea::make('childs_strengths')->required()->live(),
                    Forms\Components\Textarea::make('areas_of_concern')->required()->live(),
                    Forms\Components\Textarea::make('behavioral_observations')->required()->live(),
                    Forms\Components\Textarea::make('academic_performance')->required()->live(),
                    Forms\Components\Textarea::make('social_interactions')->required()->live(),
                ]),
                Tabs\Tab::make('Assessments')->schema([
                    Forms\Components\Textarea::make('cognitive_assessment')->live(),
                    Forms\Components\Textarea::make('emotional_assessment')->live(),
                    Forms\Components\Textarea::make('physical_assessment')->live(),
                    Forms\Components\Textarea::make('communication_skills')->live(),
                    Forms\Components\Textarea::make('classroom_environment')->live(),
                    Forms\Components\Textarea::make('teacher_interaction')->live(),
                    Forms\Components\Textarea::make('peer_interaction')->live(),
                ]),
                Tabs\Tab::make('Goals & Strategies')->schema([
                    Forms\Components\Textarea::make('professional_recommendations')->required()->live(),
                    Forms\Components\Textarea::make('short_term_goals')->required()->live(),
                    Forms\Components\Textarea::make('long_term_goals')->required()->live(),
                    Forms\Components\Textarea::make('intervention_strategies')->live(),
                    Forms\Components\Textarea::make('classroom_accommodations')->live(),
                ]),
                Tabs\Tab::make('Follow Up')->schema([
                    Forms\Components\Textarea::make('next_steps')->live(),
                    Forms\Components\DatePicker::make('recommended_follow_up_date')->live(),
                    Forms\Components\Select::make('parent_meeting_required')
                        ->options([
                            'urgent-within--weeks' => 'Urgent - Within -- Weeks',
                            'urgent-within-1-weeks' => 'Urgent - Within 1 Week',
                            'soon-within-2-weeks' => 'Soon - Within 2 Weeks',
                            'routine-within-1-month' => 'Routine - Within 1 Month',
                            'not-required' => 'Not Required',
                        ])
                        ->required()
                        ->live(),
                ]),
            ]),
        ];
    }
}
