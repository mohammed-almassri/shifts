<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeesRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Core\App\Traits\IsController;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function index(){
        $data = Employee::all();
        return $this->successResponse(EmployeeResource::collection($data),'');
    }

    public function store(StoreEmployeesRequest $request){
        \DB::beginTransaction();
        try{
            $e = Employee::create($request->all());
            \DB::commit();
            return $this->successResponse([],'',201);
        }
        catch(\Exception $e){
            
            \DB::rollback();
            return $this->errorResponse([], '',500,$e);
        }
    }
}
