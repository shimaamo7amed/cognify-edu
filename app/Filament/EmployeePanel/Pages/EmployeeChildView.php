<?php

namespace App\Filament\EmployeePanel\Pages;

use Filament\Pages\Page;
use App\Models\CognifyChild;

class EmployeeChildView extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.employee-panel.pages.employee-child-view';

    public ?CognifyChild $child = null;

    public function mount($childId): void
    {
        $this->child = CognifyChild::find($childId);

        if (!$this->child) {
            abort(404, "Child not found");
        }

        // تحقق من صلاحيات الموظف
        $employee = auth('employee')->user();
        if (!$employee->assignedChildren->contains($this->child)) {
            abort(403, "You don't have permission to view this child");
        }
    }

    // استخدم getRoutePath بدلاً من getSlug
    public static function getRoutePath(): string
    {
        return '/employee-child-view/{childId}';
    }
}
