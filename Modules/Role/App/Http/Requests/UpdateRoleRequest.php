<?php

namespace Modules\Role\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|in:0,1',
            'guard_name' => 'sometimes|string',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'The role title is required.',
            'name.string' => 'The title must be a valid string.',
            'name.max' => 'The title may not be greater than 255 characters.',
            'name.unique' => 'This role title already exists.',
            'status.boolean' => 'The status must be true or false.',
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
