<?php

namespace App\Models;

use Core\App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShiftStatus extends BaseModel
{
    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
