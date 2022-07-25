<?php

namespace App\Http\Requests;

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
            'task_id' => ['required', 'integer'],
            'project_id' => ['sometimes', 'integer'],
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'max:1000'],
            'priority' => ['sometimes', 'numeric', ],
        ];
    }
}
