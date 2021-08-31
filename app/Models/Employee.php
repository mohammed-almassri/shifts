<?php

namespace App\Models;

use App\Helpers\Helpers;
use Core\App\Models\BaseModel;
use Core\App\Traits\HasFilters;

class Employee extends BaseModel
{
    protected $fillable = ['name'];

    public function shifts(){
        return $this->hasMany(Shift::class,'employee_id');
    }

    public function shiftStatuses(){
        return $this->hasMany(ShiftStatus::class,'employee_id');
    }

    public function offTime($date){
        $shifts = $this->shifts()->whereDate('shift_date',$date)->get();
        $statuses = $this->shiftStatuses()->whereDate('status_date',$date)->get();
        $off_time_seconds = 0;
        foreach($shifts as $shift){
            $shift_start = Helpers::timeToSec($shift->start_time);
            $shift_end = Helpers::timeToSec($shift->end_time);
            $off_time_seconds += $shift_end-$shift_start;
            
            $filtered_statuses = $statuses->filter(function($status) use($shift){
                return $shift->start_time<=$status->end_time && $shift->end_time>$status->start_time;
            });

            foreach($filtered_statuses as $status){
                $status_start = Helpers::timeToSec($status->start_time);
                $status_end = Helpers::timeToSec($status->end_time);
                $off_time_seconds -= min($shift_end,$status_end)-max($shift_start,$status_start);
            }
        }
        return Helpers::secToTime($off_time_seconds);
    }
}
