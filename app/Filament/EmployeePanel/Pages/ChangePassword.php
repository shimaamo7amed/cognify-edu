<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static string $view = 'filament.pages.change-password';
    protected static ?string $title = 'Change Password';
    protected static ?string $navigationLabel = 'Change Password';

    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('current_password')
                    ->label('Current Password')
                    ->password()
                    ->required()
                    ->rules(['required']),

                Forms\Components\TextInput::make('new_password')
                    ->label('New Password')
                    ->password()
                    ->required()
                    ->rules(['required', 'min:8']),

                Forms\Components\TextInput::make('new_password_confirmation')
                    ->label('Confirm New Password')
                    ->password()
                    ->required()
                    ->same('new_password')
                    ->rules(['required']),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $user = auth()->user();

        if (! Hash::check($this->data['current_password'], $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->data['new_password']),
        ]);

        session()->flash('success', 'Password updated successfully.');
    }
}

