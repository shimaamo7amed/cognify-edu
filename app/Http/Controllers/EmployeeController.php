<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\EmployeeServices;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;

class EmployeeController extends Controller
{
    public function EmployeesRegister(EmployeeRequest $request)
    {
        EmployeeServices::employeeRegister($request->validated());
        return apiResponse(true, [], __('messages.employee_success'));

    }
}
