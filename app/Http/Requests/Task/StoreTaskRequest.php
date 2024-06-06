<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;

class StoreTaskRequest extends FormRequest
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
            'titre' => 'required|string|max:255|unique:tasks',
            'description' => 'sometimes|string|max:255',
            'statut' => 'sometimes|in:Waiting,Processing,Completed',
            'due_date' => ['required', 'after_or_equal:' . now()->format('Y-m-d')]
        ];
    }

    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            response()->json([
                'success'   => false,
                'message'   => 'Validation errors',
                'errors'      => $validator->errors()
            ], Response::HTTP_BAD_REQUEST)
        );
    }
}
