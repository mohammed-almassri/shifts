<?php

namespace Core\App\Http\Requests;

use Core\App\Traits\SendsApiResponse;
use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    use SendsApiResponse;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function failedValidation(Validator $validator) {
          
            throw new HttpResponseException(    
                $this->validationErrorResponse($validator->errors(), __('lang.validation_error'),422)
            );
    }
}
