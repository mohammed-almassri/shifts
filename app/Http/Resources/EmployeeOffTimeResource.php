<?php

namespace App\Http\Resources;

use App\Models\Employee;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeOffTimeResource extends JsonResource
{
    private $date;
    public function __construct($resource, $date)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        
        $this->date = $date;
    }

    public function toArray($request)
    {
        $time = $this->offTime($this->date);
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'created_at'=>$this->created_at,
            'off_time'=>$time,
        ];
    }
}
