<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'titre' => 'sometimes|string|max:255|unique:tasks'.$this->id,
            'description' => 'sometimes|string|max:255',
            'statut' => 'sometimes|in:Waiting,Processing,Completed',
            'due_date' => ['sometimes', 'after_or_equal:' . now()->format('Y-m-d')]
        ];
    }
}
