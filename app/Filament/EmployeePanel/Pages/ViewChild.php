<?php

namespace App\Filament\EmployeePanel\Pages;

use Filament\Pages\Page;
use App\Models\CognifyChild;

class ViewChild extends Page
{
    protected static string $view = 'filament.employee-panel.pages.view-child';
    protected static ?string $panel = 'employeePanel';

    public ?CognifyChild $child = null;

    protected static bool $shouldRegisterNavigation = false;

    public function mount($childId): void
    {
        \Log::info("Searching for CognifyChild with ID: " . $childId);

        $this->child = CognifyChild::find($childId);

        if (!$this->child) {
            \Log::error("CognifyChild with ID {$childId} not found");
            abort(404, "Child not found");
        }

        \Log::info("CognifyChild found: " . $this->child->id);
    }

    public static function getSlug(): string
    {
        return 'view-child/{childId}';
    }
}
