<?php

namespace App\Models;

use Core\App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShiftStatus extends BaseModel
{
    protected $fillable = ['start_time','end_time','status_date','employee_id'];
    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
