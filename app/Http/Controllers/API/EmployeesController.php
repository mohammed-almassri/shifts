<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeesRequest;
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
}
