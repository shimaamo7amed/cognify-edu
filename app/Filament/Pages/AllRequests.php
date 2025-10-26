<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use App\Models\ObservationChildCase;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\EmployeeChild;

class AllRequests extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'All Requests';
    protected static ?string $slug = 'all-requests';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static string $view = 'filament.pages.all-requests';
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
    
        // نتأكد إن فيه مستخدم أولاً
        if (!$user) {
            return false;
        }
    
        // السماح فقط للمستخدم اللي عنده دور teacher
        return $user->hasRole('teacher');
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(function () {
                $query = ObservationChildCase::query()->with('child.parent');

                // لو Teacher، يجيب بس الأطفال assigned ليه
                if (auth()->user()->hasRole('teacher')) {
                    $assignedChildrenIds = EmployeeChild::where('employee_id', auth()->id())
                        ->pluck('child_id');

                    $query->where(function ($q) use ($assignedChildrenIds) {
                        $q->where('employee_id', auth()->id())
                            ->orWhereIn('child_id', $assignedChildrenIds);
                    });
                }

                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Request ID'),
                Tables\Columns\TextColumn::make('child.parent.name')->label('Parent'),
                Tables\Columns\TextColumn::make('child.fullName')->label('Child'),
                Tables\Columns\TextColumn::make('child.schoolName')->label('School'),
                Tables\Columns\TextColumn::make('slot_date')->label('Date')->dateTime('Y-m-d'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->color(fn ($state) => match ($state) {
                        'new_request' => 'primary',
                        'under_review' => 'warning',
                        'approved' => 'success',
                        'schedualed' => 'info',
                        'assigned' => 'secondary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state))),

                Tables\Columns\BadgeColumn::make('child.priority')
                    ->label('Priority')
                    ->color(fn ($state) => match (strtolower($state)) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'success',
                        'intensive' => 'info',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                // ✅ استبدال request()->segment(1) بالـ getUrl
                Tables\Columns\TextColumn::make('view_link')
                    ->label('')
                    ->html()
                    ->getStateUsing(
                        fn ($record) =>
                        '<a href="' . \App\Filament\Pages\ViewRequest::getUrl(['record' => $record->id]) . '" 
                            class="text-sm text-primary-600 hover:underline font-medium">
                            View
                        </a>'
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn ($record) => \App\Filament\Pages\ViewRequest::getUrl(['record' => $record->id]))
                    ->icon('heroicon-o-eye')
                    ->visible(fn ($record) => $record->employee_id === auth()->id()),

                Tables\Actions\Action::make('edit_report')
                    ->label('Create Report')
                    ->icon('heroicon-o-document-text')
                    ->url(function (ObservationChildCase $record) {
                        $employeeId = auth()->id();

                        if ($record->employee_id === $employeeId) {
                            return \App\Filament\Pages\CreateReportPage::getUrl([
                                'record' => $record->child_id,
                            ]);
                        }

                        $inEmployeeChild = EmployeeChild::where('child_id', $record->child_id)
                            ->where('employee_id', $employeeId)
                            ->exists();

                        if ($inEmployeeChild) {
                            return url('/employee/daily-report/create?record=' . $record->child_id);
                        }

                        return null;
                    })
                    ->visible(function (ObservationChildCase $record) {
                        return $record->employee_id === auth()->id()
                            || EmployeeChild::where('child_id', $record->child_id)
                                ->where('employee_id', auth()->id())
                                ->exists();
                    }),

                Tables\Actions\Action::make('view_child')
                    ->label('View Child')
                    ->icon('heroicon-o-user')
                    ->url(fn ($record) => url('/employee/employee-child-view/' . $record->child->id))
                    ->visible(function ($record) {
                        if (!$record->child) {
                            return false;
                        }
                        return \App\Models\EmployeeChild::where('employee_id', auth()->id())
                            ->where('child_id', $record->child->id)
                            ->exists();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
