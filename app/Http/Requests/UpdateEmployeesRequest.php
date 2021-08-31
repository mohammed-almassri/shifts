<?php

namespace App\Http\Requests;

use Core\App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeesRequest extends BaseRequest
{
  
    public function rules()
    {
        return [
            'name'=>'required',
        ];
    }
}
