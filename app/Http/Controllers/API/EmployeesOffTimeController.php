<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeOffTimeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeesOffTimeController extends Controller
{
    public function show(Employee $employee,$date){
        $off_time = $employee->offTime($date);
       return new EmployeeOffTimeResource($employee,$off_time);
    }
}
