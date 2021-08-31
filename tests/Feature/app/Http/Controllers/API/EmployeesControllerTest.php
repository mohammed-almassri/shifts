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
        $response = $this->postJson(route('employees.store',$data));
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
        $response = $this->postJson(route('employees.store',$data));
        $response->assertJsonValidationErrors(['name'=>'required']);
        $this->assertCount(0,Employee::all());
   }

   private function getData(){
       return [
           'name'=>$this->faker->name(),
       ];
   }
}








