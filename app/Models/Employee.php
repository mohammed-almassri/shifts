<?php

namespace App\Models;

use Core\App\Models\BaseModel;
use Core\App\Traits\HasFilters;

class Employee extends BaseModel
{
    protected $fillable = ['name'];

}
