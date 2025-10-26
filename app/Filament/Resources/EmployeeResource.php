<?php
namespace App\Filament\Resources;
use Filament\Forms;
use Filament\Tables;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\CognifyChild;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use App\Models\ObservationChildCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static bool $shouldRegisterNavigation = false;

    public static function getModelLabel(): string
    {
        return __('filament/Employee.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/Employee.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament/Employee.personal_information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament/Employee.first_name'))
                            ->required(),
                        TextInput::make('middle_name')
                            ->label(__('filament/Employee.middle_name'))
                            ->required(),
                        TextInput::make('last_name')
                            ->label(__('filament/Employee.last_name'))
                            ->required(),
                        TextInput::make('date_of_birth')
                            ->label(__('filament/Employee.date_of_birth'))
                            ->required(),
                        TextInput::make('gender')
                            ->label(__('filament/Employee.gender'))
                            ->required(),
                        TextInput::make('nationality')
                            ->label(__('filament/Employee.nationality'))
                            ->required(),
                    ])->columns(2),

                Section::make(__('Contact Information'))
                    ->schema([
                        TextInput::make('phone')
                            ->label(__('filament/Employee.phone'))
                            ->tel()
                            ->required(),
                        TextInput::make('email')
                            ->label(__('filament/Employee.email'))
                            ->email()
                            ->required(),
                        TextInput::make('street_address')
                            ->label(__('filament/Employee.street_address'))
                            ->required(),
                    ])->columns(2),

                Section::make(__('Educational Background'))
                    ->schema([
                        TextInput::make('highest_degree')
                            ->label(__('filament/Employee.highest_degree'))
                            ->required(),
                        TextInput::make('institution')
                            ->label(__('filament/Employee.institution'))
                            ->required(),
                        TextInput::make('graduation_year')
                            ->label(__('filament/Employee.graduation_year'))
                            ->required(),
                    ])->columns(2),

                Section::make(__('filament/Employee.other_certifications'))
                    ->schema([
                        TextInput::make('other_institution')
                            ->label(__('filament/Employee.other_institution')),
                        TextInput::make('other_degree_title')
                            ->label(__('filament/Employee.other_degree_title')),
                        TextInput::make('other_degree_institution')
                            ->label(__('filament/Employee.other_degree_institution')),
                        TextInput::make('other_degree_year')
                            ->label(__('filament/Employee.other_degree_year')),
                    ])->columns(2),

                Section::make(__('filament/Employee.professional_experience'))
                    ->schema([
                        TextInput::make('current_position_title')
                            ->label(__('filament/Employee.current_position_title'))
                            ->required(),
                        TextInput::make('current_institution')
                            ->label(__('filament/Employee.current_institution'))
                            ->required(),
                        TextInput::make('current_from')
                            ->label(__('filament/Employee.current_from'))
                            ->required(),
                        TextInput::make('current_to')
                            ->label(__('filament/Employee.current_to'))
                            ->required(),
                        Textarea::make('current_responsibilities')
                            ->label(__('filament/Employee.current_responsibilities'))
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('previous_position_title')
                            ->label(__('filament/Employee.previous_position_title')),
                        TextInput::make('previous_institution')
                            ->label(__('filament/Employee.previous_institution')),
                        TextInput::make('previous_from')
                            ->label(__('filament/Employee.previous_from')),
                        TextInput::make('previous_to')
                            ->label(__('filament/Employee.previous_to')),
                        Textarea::make('previous_responsibilities')
                            ->label(__('filament/Employee.previous_responsibilities'))
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make(__('filament/Employee.skills_competencies'))
                    ->schema([
                        TextInput::make('total_teaching_experience')
                            ->label(__('filament/Employee.total_teaching_experience'))
                            ->required(),
                        TextInput::make('special_needs_experience')
                            ->label(__('filament/Employee.special_needs_experience'))
                            ->required(),
                        TextInput::make('shadow_teacher_experience')
                            ->label(__('filament/Employee.shadow_teacher_experience'))
                            ->required(),
                        Textarea::make('computer_skills')
                            ->label(__('filament/Employee.computer_skills'))
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make(__('filament/Employee.language_proficiency'))
                    ->schema([
                        TextInput::make('arabic_proficiency')
                            ->label(__('filament/Employee.arabic_proficiency'))
                            ->required(),
                        TextInput::make('english_proficiency')
                            ->label(__('filament/Employee.english_proficiency'))
                            ->required(),
                        TextInput::make('french_proficiency')
                            ->label(__('filament/Employee.french_proficiency')),
                        TextInput::make('italian_proficiency')
                            ->label(__('filament/Employee.italian_proficiency')),
                    ])->columns(2),

                Section::make(__('filament/Employee.training_development'))
                    ->schema([
                        TextInput::make('training_title')
                            ->label(__('filament/Employee.training_title')),
                        TextInput::make('training_date')
                            ->label(__('filament/Employee.training_date')),
                        TextInput::make('professional_memberships')
                            ->label(__('filament/Employee.professional_memberships')),
                    ])->columns(2),

                Section::make(__('filament/Employee.motivation_suitability'))
                    ->schema([
                        Textarea::make('motivation')
                            ->label(__('filament/Employee.motivation'))
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('suitability')
                            ->label(__('filament/Employee.suitability'))
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make(__('filament/Employee.status'))
                    ->schema([
                        TextInput::make('is_approved')
                            ->label(__('filament/Employee.is_approved'))
                            ->required(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament/Employee.first_name'))
                    ->getStateUsing(fn ($record) => $record->name . ' ' . $record->middle_name)
                    ->searchable(['name', 'middle_name']),
                TextColumn::make("email")
                    ->label(__('filament/Employee.email')),
                    TextColumn::make("current_position_title")
                    ->label(__('filament/Employee.current_position_title')),
                TextColumn::make("is_approved")
                    ->label(__('filament/Employee.is_approved')),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('assignCase')
                ->label('Assign Case')
                ->form([
                    Select::make('type')
                        ->label('Assignment Type')
                        ->options([
                            'case' => 'Case',
                            'observation' => 'Observation',
                        ])
                        ->required()
                        ->live(),

                    Select::make('children')
                        ->label('Select Children')
                        ->multiple()
                        ->required()
                        ->options(function (callable $get) {
                            if ($get('type') === 'case') {
                                return \App\Models\CognifyChild::whereHas('parent', function ($query) {
                                        $query->where('step', 4);
                                    })
                                    ->whereDoesntHave('employees') 
                                    ->pluck('fullName', 'id');
                            }

                            if ($get('type') === 'observation') {
                                return \App\Models\ObservationChildCase::whereNull('employee_id')
                                    ->with('child') 
                                    ->get()
                                    ->mapWithKeys(fn ($row) => [$row->id => $row->child?->fullName ?? 'Unknown']);
                            }

                            return [];
                        }),
                ])
                ->action(function (array $data, \App\Models\Employee $record) {
                    if ($data['type'] === 'case') {
                        $record->assignedChildren()->syncWithoutDetaching($data['children']);
                    } elseif ($data['type'] === 'observation') {
                        foreach ($data['children'] as $obsCaseId) {
                            $observation = \App\Models\ObservationChildCase::find($obsCaseId);
                            if ($observation && is_null($observation->employee_id)) {
                                $observation->employee_id = $record->id;
                                $observation->status = 'assigned';
                                $observation->status_updated_by = auth()->id();
                                $observation->save();
                            }
                        }
                    }
                })
                ->icon('heroicon-o-user-plus')
                ->color('primary')
                ->visible(fn (\App\Models\Employee $record) => $record->is_approved === 'accepted'),
                Action::make('accept')
                    ->label(__('filament/Employee.accept'))
                    ->requiresConfirmation()
                    ->action(function (Employee $record) {
                    // $password = Str::random(8);
                    $password = 'Shimaa@2001';
                    $record->update([
                        'is_approved' => 'accepted',
                        'password' => Hash::make($password),
                    ]);
                    $record->assignRole('teacher');
                    Mail::send('mails.employee_accepted', [
                        'employee' => $record,
                        'password' => $password,
                    ], function ($message) use ($record) {
                        $message->to($record->email)
                            ->subject('Employment application accepted');
                    });
                })
                ->color('success')
                ->icon('heroicon-o-check')
                ->visible(fn (Employee $record) => $record->is_approved === 'pending'),
               Action::make('reject')
    ->label(__('filament/Employee.reject'))
    ->requiresConfirmation()
    ->action(function (Employee $record) {
        // إرسال إيميل الرفض
        Mail::send('mails.employee_rejected', ['employee' => $record], function ($message) use ($record) {
            $message->to($record->email)
                ->subject('Employment application rejected');
        });

        // إزالة أي علاقات تابعة (اختياري)
        if (method_exists($record, 'assignedChildren')) {
            $record->assignedChildren()->detach();
        }

        // حذف نهائي من قاعدة البيانات
        $record->forceDelete();

        // إشعار نجاح في Filament
        \Filament\Notifications\Notification::make()
            ->title('Employee Rejected')
            ->body('The employee has been rejected and deleted successfully.')
            ->success()
            ->send();
    })
    ->color('danger')
    ->icon('heroicon-o-x-circle')
    ->visible(fn (Employee $record) => $record->is_approved === 'pending')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'view' => Pages\ViewEmployee::route('/{record}'),
        ];
    }
}
// Action::make('assignCase')
//     ->label('Assign Case')
//     ->form([
//         Select::make('children')
//             ->label('Select Children')
//             ->multiple()
//             ->options(
//                 CognifyChild::whereHas('parent', function($query) {
//                     $query->where('step', 3);
//                 })->pluck('fullName', 'id')
//             )
//             ->required(),
//     ])
//     ->action(function (array $data, Employee $record) {
//         $record->children()->syncWithoutDetaching($data['children']);
//     })
//     ->icon('heroicon-o-user-plus')
//     ->color('primary')
//     ->visible(fn (Employee $record) => $record->is_approved === 'accepted'),
// Action::make('assignCase')
//     ->label('Assign Case')
//     ->form([
//         Select::make('children')
//             ->label('Select Children')
//             ->multiple()
//             ->options(
//             CognifyChild::whereHas('parent', function($query) {
//                 $query->where('step', 3);
//             })
//             ->whereDoesntHave('employees')
//             ->pluck('fullName', 'id')
//         )->required(),
//     ])
//     ->action(function (array $data, Employee $record) {
//         $record->children()->syncWithoutDetaching($data['children']);
//     })
//     ->icon('heroicon-o-user-plus')
//     ->color('primary')
// ->visible(fn (Employee $record) => $record->is_approved === 'accepted'),
