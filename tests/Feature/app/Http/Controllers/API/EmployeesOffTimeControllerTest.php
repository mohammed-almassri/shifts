<?php

namespace Tests\Feature\app\Http\Controllers\API;

use App\Models\Employee;
use App\Models\Shift;
use App\Models\ShiftStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeesOffTimeControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testReturnsZeroIfEmployeeHasNoShiftsOnEmployeeOffTime()
    {
        $this->withoutExceptionHandling();
        $e = Employee::factory()->create()->first();
        $response = $this->get(route('employees.offTime.show', ['employee' => $e->id, 'date' => '2021-08-26']));
        $response->assertOk();
        $this->assertEquals('00:00:00', $response->getData()->data->off_time);
    }
    public function testCorrectAmountOfTimeBeforeShiftStartOnEmployeeOffTime()
    {
        $this->withoutExceptionHandling();
        $emp = Employee::factory()->create()->first();
        $this->createShift(
            '10:00:00',
            '12:00:00',
            $emp->id
        );

        $this->createStatus(
            '09:30:00',
            '11:30:00',
            $emp->id,
        );
        $response = $this->get(route('employees.offTime.show', ['employee' => $emp->id, 'date' => '2021-08-26']));
        $response->assertOk();
        $this->assertEquals('00:30:00', $response->getData()->data->off_time);
    }
    public function testCorrectAmountOfTimeInsideShiftOnEmployeeOffTime()
    {
        $this->withoutExceptionHandling();
        $emp = Employee::factory()->create()->first();
        $this->createShift(
            '10:00:00',
            '12:00:00',
            $emp->id
        );
        $this->createStatus(
            '10:30:00',
            '11:30:00',
            $emp->id,
        );
        $response = $this->get(route('employees.offTime.show', ['employee' => $emp->id, 'date' => '2021-08-26']));
        $response->assertOk();
        $this->assertEquals('01:00:00', $response->getData()->data->off_time);
    }
    public function testCorrectAmountOfTimeAroundShiftOnEmployeeOffTime()
    {
        $this->withoutExceptionHandling();
        $emp = Employee::factory()->create()->first();
        $this->createShift(
            '10:00:00',
            '12:00:00',
            $emp->id
        );
        $this->createStatus(
            '09:30:00',
            '12:30:00',
            $emp->id,
        );
        $response = $this->get(route('employees.offTime.show', ['employee' => $emp->id, 'date' => '2021-08-26']));
        $response->assertOk();
        $this->assertEquals('00:00:00', $response->getData()->data->off_time);
    }
    public function testCorrectAmountOfTimeAfterShiftOnEmployeeOffTime()
    {
        $this->withoutExceptionHandling();
        $emp = Employee::factory()->create()->first();
        $this->createShift(
            '10:00:00',
            '12:00:00',
            $emp->id
        );
        $this->createStatus(
            '10:30:00',
            '12:30:00',
            $emp->id,
        );
        $response = $this->get(route('employees.offTime.show', ['employee' => $emp->id, 'date' => '2021-08-26']));
        $response->assertOk();
        $this->assertEquals('00:30:00', $response->getData()->data->off_time);
    }
    public function testCorrectAmountOfTimeMultipleShiftsOnEmployeeOffTime()
    {
        $this->withoutExceptionHandling();
        $emp = Employee::factory()->create()->first();
        $this->createShift('10:00:00', '12:00:00', $emp->id);
        $this->createShift('12:30:00', '14:30:00', $emp->id);
        $this->createShift('15:00:00', '17:00:00', $emp->id);
        $this->createShift('17:30:00', '19:30:00', $emp->id);
        $this->createStatus('09:45:00', '12:15:00', $emp->id);
        $this->createStatus('13:00:00', '14:15:00', $emp->id);
        $this->createStatus('14:50:00', '15:05:00', $emp->id);
        $this->createStatus('15:10:00', '16:00:00', $emp->id);
        $this->createStatus('16:10:00', '16:30:00', $emp->id);
        $this->createStatus('17:15:00', '17:20:00', $emp->id);
        $this->createStatus('17:40:00', '17:50:00', $emp->id);
        $this->createStatus('19:45:00', '19:50:00', $emp->id);
        $response = $this->get(route('employees.offTime.show', ['employee' => $emp->id, 'date' => '2021-08-26']));
        $response->assertOk();
        $this->assertEquals('03:20:00', $response->getData()->data->off_time);
    }

    public function testWorksOnlyForCorrectDayOnEmployeeOffTime()
    {
        $emp = Employee::factory()->create()->first();
        $this->createShift('10:00:00', '12:00:00', $emp->id);
        $this->createShift('12:30:00', '14:30:00', $emp->id);
        $this->createShift('15:00:00', '17:00:00', $emp->id);
        $this->createShift('17:30:00', '19:30:00', $emp->id);
        $this->createStatus('09:45:00', '12:15:00', $emp->id);
        $this->createStatus('13:00:00', '14:15:00', $emp->id);
        $this->createStatus('14:50:00', '15:05:00', $emp->id);
        $this->createStatus('15:10:00', '16:00:00', $emp->id);
        $this->createStatus('16:10:00', '16:30:00', $emp->id);
        $this->createStatus('17:15:00', '17:20:00', $emp->id);
        $this->createStatus('17:40:00', '17:50:00', $emp->id);
        $this->createStatus('19:45:00', '19:50:00', $emp->id);
        $response = $this->get(route('employees.offTime.show', ['employee' => $emp->id, 'date' => '2021-08-27']));
        $response->assertOk();
        $this->assertEquals('00:00:00', $response->getData()->data->off_time);
    }

    private function createShift($start, $end, $employee_id, $date = '2021-08-26')
    {
        return Shift::create(['start_time' => $start, 'end_time' => $end, 'shift_date' => $date, 'employee_id' => $employee_id,]);
    }
    private function createStatus($start, $end, $employee_id, $date = '2021-08-26')
    {
        return ShiftStatus::create(['start_time' => $start, 'end_time' => $end, 'status_date' => $date, 'employee_id' => $employee_id,]);
    }
}
