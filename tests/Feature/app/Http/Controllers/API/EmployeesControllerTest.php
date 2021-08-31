<?php

namespace Tests\Feature\app\Http\Controllers\API;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeesControllerTest extends TestCase
{
    use WithFaker,RefreshDatabase;
   public function testCanIndexEmployees(){
       $this->withoutExceptionHandling();
       Employee::factory(10)->create();
       $response = $this->getJson(route('employees.index'));
       $response->assertOk();
       $response->assertJsonStructure([
            'data'=>[
                '*'=>[
                    'id',
                    'name',
                    'created_at',
                ]
            ],
            'links',
            'meta'
       ]);
   }

   public function testCanCreateEmployees(){
        $data = $this->getData();
        $response = $this->postJson(route('employees.store'),$data);
        $response->assertCreated();
        $this->assertCount(1,Employee::all());
        $this->assertDatabaseHas('employees',$data);
        $response->assertJsonStructure([
            'data'=>[
                'id',
                'name',
                'created_at',
            ]
       ]);
   }

   public function testNameIsRequiredOnCreateEmployees(){
        $data = $this->getData();
        $data['name'] = '';
        $response = $this->postJson(route('employees.store'),$data);
        $response->assertJsonValidationErrors(['name'=>'required']);
        $this->assertCount(0,Employee::all());
   }

   public function testCanShowEmployees(){
        $this->withoutExceptionHandling();
        $employee = Employee::factory()->create();
        $response = $this->getJson(route('employees.show',['employee'=>$employee->id]));
        $response->assertOk();
        $response->assertJsonStructure([
            'data'=>[
                    'id',
                    'name',
                    'created_at',
            ],
        ]);
   }

   public function testCanUpdateEmployees(){
        $employee = Employee::factory()->create();
        $data = [
            'name'=>$employee->name.'2'
        ];
        $response = $this->patchJson(route('employees.update',['employee'=>$employee->id]),$data);
        dump($response->getData());
        $response->assertOk();
        $this->assertCount(1,Employee::all());
        $this->assertDatabaseHas('employees',$data);
        $response->assertJsonStructure([
                'data'=>[
                    'id',
                    'name',
                    'created_at',
                ]
        ]);
    }

    public function testNameIsRequiredOnUpdateEmployees(){
        $employee = Employee::factory()->create();
        $data = $this->getData();
        $data['name'] = '';
        $response = $this->patchJson(route('employees.update',['employee'=>$employee->id]),$data);
        $response->assertJsonValidationErrors(['name'=>'required']);
        $this->assertCount(1,Employee::all());
    }

    public function testCanDeleteEmployees()
    {
        $employee = Employee::factory()->create();
        $response = $this->deleteJson(route('employees.destroy',['employee'=>$employee->id]));
        $response->assertNoContent();
        $this->assertCount(0,Employee::all());
    }

   private function getData(){
       return [
           'name'=>$this->faker->name(),
       ];
   }
}








