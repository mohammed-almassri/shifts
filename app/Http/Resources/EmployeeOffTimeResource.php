<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use App\Models\Employee;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeOffTimeResource extends JsonResource
{
    private $time;
    public function __construct($resource, $time)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        
        $this->time = $time;
    }

    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'created_at'=>$this->created_at,
            'off_time'=>Helpers::secToTime($this->time),
        ];
    }
}
