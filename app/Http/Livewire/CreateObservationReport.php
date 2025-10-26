<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\ObservationReport;
use App\Models\ObservationChildCase;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateObservationReport extends Component implements HasForms
{
    use InteractsWithForms;

    public $record;
    public ?ObservationChildCase $requestCase = null;
    public array $formData = [];
    public int $progress = 0;

    public Form $form;

    public function mount($record): void
    {
        $this->record = $record;
        $this->requestCase = ObservationChildCase::with(['child', 'employee', 'session'])->findOrFail($record);

        $this->form = $this->makeForm();

        $existingReport = ObservationReport::where('observation_child_case_id', $record)->first();

        if ($existingReport) {
            $this->form->fill($existingReport->toArray());
        } else {
            $this->form->fill([
                'observation_date' => $this->requestCase->slot_date,
                'observer_name' => $this->requestCase->employee->name ?? '',
                'observer_title' => $this->requestCase->session->name['en'] ?? '',
                'session_duration' => $this->requestCase->session->duration ?? '',
                'observation_location' => $this->requestCase->child->school_name ?? '',
            ]);
        }

        $this->formData = $this->form->getState();
        $this->progress = $this->calculateProgress();
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'formData.')) {
            $this->progress = $this->calculateProgress();
        }
    }

    protected function makeForm(): Form
    {
        return $this->form(
            $this->form(new ObservationReport())
                ->schema($this->getFormSchema())
                ->statePath('formData')
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('formData')
            ->model(ObservationReport::class);
    }

    public function submit(bool $finalize = false)
    {
            $data = $this->formData;
            $this->progress = $this->calculateProgress();
            $report = ObservationReport::updateOrCreate(
            [
                    'observation_child_case_id' => $this->requestCase->id,
                ],
            array_merge($data, [
                    'child_id' => $this->requestCase->child_id,
                    'progress' => $this->progress,
                    'status' => $finalize && $this->progress === 100 ? 'finalized' : 'draft',
                ])
            );

        session()->flash('success', $finalize ? 'Report finalized successfully.' : 'Draft saved.');

        return redirect()->route('dashboard');    }
    public function calculateProgress(): int
    {
        $fields = $this->extractFields($this->getFormSchema());
        $totalFields = count($fields) ?: 1;

        $filledFields = collect($fields)
            ->map(fn ($field) => $field->getName())
            ->filter(fn ($key) => array_key_exists($key, $this->formData) && !is_null($this->formData[$key]) && $this->formData[$key] !== '' && $this->formData[$key] !== [])
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

            if (method_exists($component, 'getSchema')) {
                $fields = array_merge($fields, $this->extractFields($component->getSchema()));
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
                    Forms\Components\TextInput::make('observer_name')->required()->live(),
                    Forms\Components\TextInput::make('observer_title')->live(),
                    Forms\Components\TextInput::make('session_duration')->live(),
                    Forms\Components\TextInput::make('observation_location')->live(),
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
                    Forms\Components\Select::make('parent_meeting_required')->required()->options([
                        'urgent-within--weeks' => 'Urgent - Within -- Weeks',
                        'urgent-within-1-weeks' => 'Urgent - Within 1 Week',
                        'soon-within-2-weeks' => 'Soon - Within 2 Weeks',
                        'routine-within-1-month' => 'Routine - Within 1 Month',
                        'not-required' => 'Not Required',
                    ])->live(),
                ]),
            ]),
        ];
    }

    public function render()
    {
        return view('livewire.create-observation-report');
    }
}
