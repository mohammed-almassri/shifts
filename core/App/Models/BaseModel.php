<?php

namespace Core\App\Models;

use App\Traits\HasUuid;
use App\Traits\HasFilters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
class BaseModel extends Model
{
    use HasFactory,HasUuid,SoftDeletes,HasTranslations,HasFilters;

    protected $translatable = [];


}