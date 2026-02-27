<?php

namespace Modules\Planner\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTimeEstimateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'task_id' => ['required'],
            'users' => ['required', 'array'],
            'users.*' => ['nullable', 'numeric', 'min:0']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
