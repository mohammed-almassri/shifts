<?php

namespace App\Http\Requests;

use Core\App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeesRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name'=>'required',
        ];
    }
}
