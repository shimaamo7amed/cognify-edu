<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Models\ObservationChildCase;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.admin.pages.dashboard';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';

    public $cases;

    public function mount(): void
    {
        $this->cases = ObservationChildCase::with('child')
            ->latest()
            ->take(3)
            ->get();
    }
}
