<?php

namespace Core\App\Models;

use Core\App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BaseModel extends Model
{
    use HasFactory,HasUuid,SoftDeletes;

    protected $translatable = [];


}