<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeesRequest;
use App\Http\Requests\UpdateEmployeesRequest;
use App\Http\Resources\EmployeeOffTimeResource;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Core\App\Traits\IsController;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function index(){
        return EmployeeResource::collection(Employee::paginate());
    }

    public function store(StoreEmployeesRequest $request){
        $employee = Employee::create($request->validated());
        return new EmployeeResource($employee);
    }

    public function show(Employee $employee){
        return new EmployeeResource($employee);
    }

    public function update(UpdateEmployeesRequest $request, Employee $employee){
        $employee->update($request->validated());
        return new EmployeeResource($employee);
    }

    public function destroy(Employee $employee){
        $employee->delete();
        return response()->json([],204);
    }
}
