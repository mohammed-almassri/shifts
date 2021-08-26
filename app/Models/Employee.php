<?php

namespace App\Models;

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
        $ret = \DB::table('shifts as s')
        ->selectRaw('
       Sec_to_time(Sum(Time_to_sec(timediff(
            timediff(s.end_time,s.start_time)
                ,
            (select on_time from (
            SELECT shifts.id,
                   Sec_to_time(Sum(Time_to_sec(Timediff(Least(shifts.end_time, ss.end_time),
                                               Greatest(shifts.start_time, ss.start_time))))
                   ) AS
                   on_time
            FROM   `shifts`
                   INNER JOIN `shift_statuses` AS `ss`
                           ON `shifts`.`start_time` <= `ss`.`end_time`
                              AND `shifts`.`end_time` > `ss`.`start_time`
                              AND `shifts`.`employee_id` = `ss`.`employee_id`
                              AND `shifts`.`shift_date` <= `ss`.`status_date`
            WHERE  Date(`shifts`.`shift_date`) = "'.$date.'"
                   AND `shifts`.`employee_id` = "'.$this->id.'"
                   AND `shifts`.`deleted_at` IS NULL
                    and `shifts`.id = s.id
            GROUP  BY shifts.id
            ) as u2)
            )))) as off_time
        ')
        ->get();



        // $ret =  Shift::selectRaw("
        // sum(timediff(
        //     timediff(shifts.end_time,shifts.start_time),
        //     timediff(least(shifts.end_time,ss.end_time),greatest(shifts.start_time,ss.start_time))
        // )) as off_time
        // ")
        // ->join('shift_statuses as ss',function($join){
        //     $join->on('shifts.start_time','<=','ss.end_time')
        //     ->on('shifts.end_time','>','ss.start_time')
        //     ->on('shifts.employee_id','=','ss.employee_id')
        //     ->on('shifts.shift_date','<=','ss.status_date')
        //     ;
        // })
        // ->whereDate('shifts.shift_date',$date)
        // ->where('shifts.employee_id',$this->id)
        // ->toSql() 
        // ;
        if($ret->first() && $ret->first()->off_time){
            return $ret->first()->off_time;
        }
        return '00:00:00';
    }
}
