<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string',
            'password' => 'required',
        ];
    }

    public function failedValidation(Validator $validator){

        throw new HttpResponseException(
            response()->json([
                'success'   => false,
                'message'   => 'Validation errors',
                'errors'      => $validator->errors()
            ],Response::HTTP_BAD_REQUEST)
        );
    }
}
