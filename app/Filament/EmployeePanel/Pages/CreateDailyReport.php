<?php

namespace App\Filament\EmployeePanel\Pages;

use App\Models\DailyReport;
use App\Models\CognifyChild;
use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateDailyReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Create Daily Report';
    protected static ?string $slug = 'daily-report/create';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.employee-panel.pages.create-daily-report';

    public array $formData = [];
    public int $progress = 0;
    public ?Carbon $lastSavedAt = null;

    // public function mount(): void
    // {
    //     $this->formData = [
    //         'report_date' => now()->format('Y-m-d'),
    //         'employee_id' => Auth::id(),
    //         'employee_name' => trim(Auth::user()->name . ' ' . (Auth::user()->middle_name ?? '') . ' ' . (Auth::user()->last_name ?? '')),
    //         'status' => 'draft',
    //         'report_type' => 'daily',
    //     ];

    //     $existingDraft = DailyReport::where('employee_id', Auth::id())
    //             ->where('status', 'draft')
    //             ->whereDate('report_date', now())
    //             ->first();

    //     if ($existingDraft) {
    //         $this->formData = array_merge($this->formData, $existingDraft->toArray());
    //         $this->lastSavedAt = $existingDraft->updated_at;
    //     }

    //     $this->progress = $this->calculateProgress();

    // }
    public function mount(): void
    {
        $draftId = request()->get('draft_id');

        $this->formData = [
            'report_date' => now()->format('Y-m-d'),
            'employee_id' => Auth::id(),
            'employee_name' => trim(Auth::user()->name . ' ' . (Auth::user()->middle_name ?? '') . ' ' . (Auth::user()->last_name ?? '')),
            'status' => 'draft',
            'report_type' => 'daily',
        ];

        if ($draftId) {
            $existingDraft = DailyReport::findOrFail($draftId);

            // أتأكد إن الداتا بتاعة الـ draft هي اللي تـ fill الفورم
            $this->formData = array_merge($this->formData, $existingDraft->toArray());
            $this->lastSavedAt = $existingDraft->updated_at;
        } else {
            // لو مفيش draft_id، هتجيب اللي موجود بنفس الطريقة القديمة
            $existingDraft = DailyReport::where('employee_id', Auth::id())
                    ->where('status', 'draft')
                    ->whereDate('report_date', now())
                    ->first();

            if ($existingDraft) {
                $this->formData = array_merge($this->formData, $existingDraft->toArray());
                $this->lastSavedAt = $existingDraft->updated_at;
            }
        }

        $this->progress = $this->calculateProgress();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('formData')
            ->model(DailyReport::class);
    }

      
    // public function saveDraft()
    // {
    //     $data = $this->formData;

    //     DailyReport::updateOrCreate(
    //         [
    //             'id' => $data['id'] ?? null, // لو فيه ID هيعمل update
    //         ],
    //         array_merge($data, [
    //             'status' => 'draft',
    //             'report_type' => 'daily',
    //         ])
    //     );

    //     $this->lastSavedAt = now();
    //     $this->progress = $this->calculateProgress();

    //     Notification::make()
    //         ->title('Draft saved successfully.')
    //         ->success()
    //         ->send();
    // }
    // public function finalizeReport()
    // {
    //     if ($this->calculateProgress() < 100) {
    //         Notification::make()
    //             ->title('Complete all required fields to finalize.')
    //             ->danger()
    //             ->send();
    //         return;
    //     }

    //     $data = $this->formData;

    //     DailyReport::updateOrCreate(
    //         [
    //             'id' => $data['id'] ?? null,
    //         ],
    //         array_merge($data, [
    //             'status' => 'finalized',
    //             'report_type' => 'daily',
    //         ])
    //     );

    //     $this->formData['status'] = 'finalized';
    //     $this->lastSavedAt = now();
    //     $this->progress = 100;

    //     Notification::make()
    //         ->title('Daily Report finalized successfully.')
    //         ->success()
    //         ->send();
    // }
        public function saveDraft()
{
    $data = $this->formData;

    DailyReport::updateOrCreate(
        [
            'employee_id' => Auth::id(),
            'child_id' => $data['child_id'],
            'report_date' => $data['report_date'],
        ],
        array_merge($data, [
            'status' => 'draft',
            'report_type' => 'daily',
        ])
    );

    $this->lastSavedAt = now();
    $this->progress = $this->calculateProgress();

    Notification::make()
        ->title('Draft saved successfully.')
        ->success()
        ->send();
}
public function finalizeReport()
{
    if ($this->calculateProgress() < 100) {
        Notification::make()
            ->title('Complete all required fields to finalize.')
            ->danger()
            ->send();
        return;
    }

    $data = $this->formData;

    DailyReport::updateOrCreate(
        [
            'employee_id' => Auth::id(),
            'child_id' => $data['child_id'],
            'report_date' => $data['report_date'],
        ],
        array_merge($data, [
            'status' => 'finalized',
            'report_type' => 'daily',
        ])
    );

    $this->formData['status'] = 'finalized';
    $this->lastSavedAt = now();
    $this->progress = 100;

    Notification::make()
        ->title('Daily Report finalized successfully.')
        ->success()
        ->send();
}




    public function calculateProgress(): int
    {
        $fields = $this->extractFields($this->getFormSchema());
        $totalFields = count($fields) ?: 1;
        $filledFields = collect($fields)
            ->map(fn ($field) => $field->getName())
            ->filter(
                fn ($key) =>
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
            Forms\Components\Tabs::make('Daily Report')
                ->tabs([

                    Forms\Components\Tabs\Tab::make('Basic Info')
                        ->schema([
                            // Forms\Components\Select::make('child_id')
                            //     ->label('Child')
                            //     // ->options(CognifyChild::whereNotNull('fullName')->pluck('fullName', 'id'))
                            //         ->options(Auth::user()->children()->pluck('fullName', 'cognify_children.id'))

                            //     ->required(),
                            Forms\Components\Select::make('child_id')
                                ->label('Child')
                                ->options(Auth::user()->assignedChildren()->pluck('fullName', 'cognify_children.id'))
                                ->required()
                                ->reactive() // علشان يراقب التغيير
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $exists = DailyReport::where('employee_id', Auth::id())
                                        ->where('child_id', $state)
                                        ->whereDate('report_date', now())
                                        ->exists();

                                    if ($exists) {
                                        Notification::make()
                                            ->title('You already submitted a report for this child today.')
                                            ->danger()
                                            ->send();

                                        // تفضيه البيانات علشان مايكتبش حاجه تاني
                                        $set('child_id', null);
                                    }
                                }),
                            Forms\Components\DatePicker::make('report_date')->required(),
                            Forms\Components\TextInput::make('employee_name')
                                ->label('Employee Name')
                                ->default(fn () => trim(
                                    Auth::user()->name . ' ' . 
                                    (Auth::user()->middle_name ?? '') . ' ' . 
                                    (Auth::user()->last_name ?? '')
                                ))
                                ->disabled(),
                    ]),

                    Forms\Components\Tabs\Tab::make('Attention & Learning')
                        ->schema([
                            Forms\Components\Textarea::make('attention_activity')
                                ->label('What activity/session caught the child\'s attention today?')->required(),

                            Forms\Components\Textarea::make('attention_level')
                                ->label('How was the child\'s focus? Did they need intervention to stay focused?')->required(),

                            Forms\Components\Textarea::make('positive_behavior')
                                ->label('Did you observe any positive behavior today? (e.g., cooperation, commitment, quick response)')->required(),
                        ]),

                    Forms\Components\Tabs\Tab::make('Communication & Social')
                        ->schema([
                            Forms\Components\Textarea::make('communication')
                                ->label('How did the child communicate? Used sentences? Words? Asked for needs?')->required(),

                            Forms\Components\Textarea::make('social_interaction')
                                ->label('How was the child\'s interaction with peers? Did they initiate or respond? Or preferred isolation?')->required(),
                        ]),

                    Forms\Components\Tabs\Tab::make('Behavior & Emotions')
                        ->schema([
                            Forms\Components\Textarea::make('meltdown')
                                ->label('Were there any meltdowns? What triggered them? How did the child calm down?')->required(),

                            Forms\Components\Textarea::make('general_behavior')
                                ->label('Overall behavior? Any actions that needed intervention? How did they respond to instructions?')->required(),
                        ]),

                    Forms\Components\Tabs\Tab::make('Academics & Independence')
                        ->schema([
                            Forms\Components\Textarea::make('academic')
                                ->label('How did the child follow academic activities? Did they understand instructions? Complete tasks?')->required(),

                            Forms\Components\Textarea::make('independence')
                                ->label('What tasks did the child do independently? What required help?')->required(),
                        ]),

                    Forms\Components\Tabs\Tab::make('Summary')
                        ->schema([
                            Forms\Components\Select::make('overall_rating')
                                ->label('Overall Day Rating')
                                ->options([
                                    1 => '1 - Very Poor',
                                    2 => '2 - Poor',
                                    3 => '3 - Average',
                                    4 => '4 - Good',
                                    5 => '5 - Excellent',
                                ])
                                ->required(),

                            Forms\Components\Textarea::make('comments')
                                ->label('Additional Comments'),
                        ]),

                ]),
        ];
    }



}
