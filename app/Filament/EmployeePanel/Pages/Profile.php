<?php

namespace App\Filament\EmployeePanel\Pages;

use Filament\Pages\Page;
use App\Models\ObservationChildCase;
use Illuminate\Support\Facades\Auth;


class Profile extends Page
{
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.employee-panel.pages.profile';

    public $user;
    public $rating = 4.9;
    public $completedCases;
    public $activeCases;
    public $casesThisMonth;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;


    public $passwordFieldsVisibility = [
        'current_password' => false,
        'new_password' => false,
        'new_password_confirmation' => false,
    ];


public function togglePasswordVisibility($field)
{
    $this->passwordFieldsVisibility[$field] = !$this->passwordFieldsVisibility[$field];
}

    public function mount(): void
    {
        $this->user = Auth::guard('employee')->user();

        $this->completedCases = ObservationChildCase::where('employee_id', $this->user->id)
            ->where('status', 'completed')
            ->count();

        $this->activeCases = ObservationChildCase::where('employee_id', $this->user->id)
            // ->where('status', 'active')
            ->count();

        $this->casesThisMonth = ObservationChildCase::where('employee_id', $this->user->id)
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    public function changePassword()
    {
        $user = auth()->user();

        if (! \Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        if ($this->new_password !== $this->new_password_confirmation) {
            $this->addError('new_password_confirmation', 'Passwords do not match.');
            return;
        }

        $user->update([
            'password' => \Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        \Filament\Notifications\Notification::make()
            ->title('Password changed successfully.')
            ->success()
            ->send();
    }
}

