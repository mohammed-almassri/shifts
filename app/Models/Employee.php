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
}
