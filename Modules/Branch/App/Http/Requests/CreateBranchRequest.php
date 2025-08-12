<?php

namespace Modules\Branch\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBranchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:branches,title,' . $this->route('branch'),
            'status' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The branch title is required.',
            'title.string' => 'The title must be a valid string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.unique' => 'This branch title already exists.',
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