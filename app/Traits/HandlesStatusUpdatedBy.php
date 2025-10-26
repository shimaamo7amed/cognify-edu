<?php


namespace App\Traits;

use App\Models\Employee;

trait HandlesStatusUpdatedBy
{
    public function fillStatusUpdatedBy($model)
    {
        $user = auth()->user();

        if ($user?->hasRole('admin')) {
            $model->status_updated_by = 'Admin User';
        } elseif ($user?->hasRole('teacher')) {
            $employeeName = Employee::find($model->employee_id)?->name ?? 'Teacher';
            $model->status_updated_by = $employeeName;
        } else {
            $model->status_updated_by = 'System';
        }
    }
}
