<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use App\Models\ObservationChildCase;
use Illuminate\Support\Facades\Auth;

class OverviewPage extends Page
{
    


    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $title = 'Overview';
    protected static ?string $slug = 'overview';
    protected static string $view = 'filament.pages.overview-page';
    protected static bool $shouldRegisterNavigation = true;

    public $cases;

    public function mount()
    {
        if (auth()->user()->hasAnyRole(['admin', 'super_admin'])) {
            $this->cases = ObservationChildCase::with('child')->latest()->take(6)->get();
        } else {
            $this->cases = ObservationChildCase::with('child')
                ->where('employee_id', auth()->id())
                ->latest()->take(6)->get();
        }
    }
}
