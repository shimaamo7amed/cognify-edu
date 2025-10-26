<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeChild extends Model
{
    protected $fillable = [
        'employee_id',
        'child_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function child()
    {
        return $this->belongsTo(CognifyChild::class, 'child_id');
    }
}
