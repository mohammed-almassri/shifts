<?php

namespace Tests\Feature\app\Http\Controllers\API;

use App\Models\Employee;
use App\Models\Shift;
use App\Models\ShiftStatus;
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
            'status',
            'message',
            'data'=>[
                '*'=>[
                    'id',
                    'name',
                    'created_at',
                ]
            ],
       ]);
   }

   public function testCanCreateEmployees(){
        $data = $this->getData();
        $response = $this->postJson(route('employees.store',$data));
        $response->assertCreated();
        $this->assertCount(1,Employee::all());
        $this->assertDatabaseHas('employees',$data);
   }

   public function testNameIsRequiredOnCreateEmployees(){
        $data = $this->getData();
        $data['name'] = '';
        $response = $this->postJson(route('employees.store',$data));
        $response->assertJsonValidationErrors(['name'=>'required']);
        $this->assertCount(0,Employee::all());
   }

   public function testReturnsZeroIfEmployeeHasNoShiftsOnEmployeeOffTime(){
       $this->withoutExceptionHandling();
       $e = Employee::factory()->create()->first();
       $response = $this->get(route('employees.offTime',['id'=>$e->id,'date'=>'2021-08-26']));
    //    dump($response->getData());
       $response->assertOk();
       $this->assertEquals('00:00:00',$response->getData()->data->off_time);
   }
   public function testCorrectAmountOfTimeBeforeShiftStartOnEmployeeOffTime(){
       $this->withoutExceptionHandling();
        $emp = Employee::factory()->create()->first();
        Shift::create([
            'start_time'=>'10:00:00',
            'end_time'=>'12:00:00',
            'shift_date'=>'2021-08-26',
            'employee_id'=>$emp->id,
        ]);
        ShiftStatus::create([
            'start_time'=>'09:30:00',
            'end_time'=>'11:30:00',
            'status_date'=>'2021-08-26',
            'employee_id'=>$emp->id,
        ]);
        $response = $this->get(route('employees.offTime',['id'=>$emp->id,'date'=>'2021-08-26']));
        $response->assertOk();
        $this->assertEquals('00:30:00',$response->getData()->data->off_time);
    }
    public function testCorrectAmountOfTimeInsideShiftOnEmployeeOffTime(){
        $this->withoutExceptionHandling();
         $emp = Employee::factory()->create()->first();
         Shift::create([
             'start_time'=>'10:00:00',
             'end_time'=>'12:00:00',
             'shift_date'=>'2021-08-26',
             'employee_id'=>$emp->id,
         ]);
         ShiftStatus::create([
             'start_time'=>'10:30:00',
             'end_time'=>'11:30:00',
             'status_date'=>'2021-08-26',
             'employee_id'=>$emp->id,
         ]);
         $response = $this->get(route('employees.offTime',['id'=>$emp->id,'date'=>'2021-08-26']));
         $response->assertOk();
         $this->assertEquals('01:00:00',$response->getData()->data->off_time);
     }
     public function testCorrectAmountOfTimeAroundShiftOnEmployeeOffTime(){
        $this->withoutExceptionHandling();
         $emp = Employee::factory()->create()->first();
         Shift::create([
             'start_time'=>'10:00:00',
             'end_time'=>'12:00:00',
             'shift_date'=>'2021-08-26',
             'employee_id'=>$emp->id,
         ]);
         ShiftStatus::create([
             'start_time'=>'09:30:00',
             'end_time'=>'12:30:00',
             'status_date'=>'2021-08-26',
             'employee_id'=>$emp->id,
         ]);
         $response = $this->get(route('employees.offTime',['id'=>$emp->id,'date'=>'2021-08-26']));
         $response->assertOk();
         $this->assertEquals('00:00:00',$response->getData()->data->off_time);
     }
     public function testCorrectAmountOfTimeAfterShiftOnEmployeeOffTime(){
        $this->withoutExceptionHandling();
         $emp = Employee::factory()->create()->first();
         Shift::create([
             'start_time'=>'10:00:00',
             'end_time'=>'12:00:00',
             'shift_date'=>'2021-08-26',
             'employee_id'=>$emp->id,
         ]);
         ShiftStatus::create([
             'start_time'=>'10:30:00',
             'end_time'=>'12:30:00',
             'status_date'=>'2021-08-26',
             'employee_id'=>$emp->id,
         ]);
         $response = $this->get(route('employees.offTime',['id'=>$emp->id,'date'=>'2021-08-26']));
         $response->assertOk();
         $this->assertEquals('00:30:00',$response->getData()->data->off_time);
     }
     public function testCorrectAmountOfTimeMultipleShiftsOnEmployeeOffTime(){
        $this->withoutExceptionHandling();
         $emp = Employee::factory()->create()->first();
         Shift::create(['start_time'=>'10:00:00','end_time'=>'12:00:00','shift_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         Shift::create(['start_time'=>'12:30:00','end_time'=>'14:30:00','shift_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         Shift::create(['start_time'=>'15:00:00','end_time'=>'17:00:00','shift_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         Shift::create(['start_time'=>'17:30:00','end_time'=>'19:30:00','shift_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         ShiftStatus::create(['start_time'=>'09:45:00', 'end_time'=>'12:15:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         ShiftStatus::create(['start_time'=>'13:00:00', 'end_time'=>'14:15:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         ShiftStatus::create(['start_time'=>'14:50:00', 'end_time'=>'15:05:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         ShiftStatus::create(['start_time'=>'15:10:00', 'end_time'=>'16:00:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         ShiftStatus::create(['start_time'=>'16:10:00', 'end_time'=>'16:30:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         ShiftStatus::create(['start_time'=>'17:15:00', 'end_time'=>'17:20:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         ShiftStatus::create(['start_time'=>'17:40:00', 'end_time'=>'17:50:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         ShiftStatus::create(['start_time'=>'19:45:00', 'end_time'=>'19:50:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
         $response = $this->get(route('employees.offTime',['id'=>$emp->id,'date'=>'2021-08-26']));
         $response->assertOk();
         $this->assertEquals('03:20:00',$response->getData()->data->off_time);
     }

     public function testWorksOnlyForCorrectDayOnEmployeeOffTime(){
        $emp = Employee::factory()->create()->first();
        Shift::create(['start_time'=>'10:00:00','end_time'=>'12:00:00','shift_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        Shift::create(['start_time'=>'12:30:00','end_time'=>'14:30:00','shift_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        Shift::create(['start_time'=>'15:00:00','end_time'=>'17:00:00','shift_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        Shift::create(['start_time'=>'17:30:00','end_time'=>'19:30:00','shift_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        ShiftStatus::create(['start_time'=>'09:45:00', 'end_time'=>'12:15:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        ShiftStatus::create(['start_time'=>'13:00:00', 'end_time'=>'14:15:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        ShiftStatus::create(['start_time'=>'14:50:00', 'end_time'=>'15:05:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        ShiftStatus::create(['start_time'=>'15:10:00', 'end_time'=>'16:00:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        ShiftStatus::create(['start_time'=>'16:10:00', 'end_time'=>'16:30:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        ShiftStatus::create(['start_time'=>'17:15:00', 'end_time'=>'17:20:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        ShiftStatus::create(['start_time'=>'17:40:00', 'end_time'=>'17:50:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        ShiftStatus::create(['start_time'=>'19:45:00', 'end_time'=>'19:50:00','status_date'=>'2021-08-26','employee_id'=>$emp->id,]);
        $response = $this->get(route('employees.offTime',['id'=>$emp->id,'date'=>'2021-08-27']));
        $response->assertOk();
        $this->assertEquals('00:00:00',$response->getData()->data->off_time);
     }


   private function getData(){
       return [
           'name'=>$this->faker->name(),
       ];
   }
}








