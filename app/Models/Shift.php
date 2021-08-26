<?php

namespace App\Models;

use Core\App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends BaseModel
{
    protected $fillable = ['start_time','end_time','shift_date','employee_id'];
    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
