<?php

namespace Modules\Zone\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateZoneRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:zones,title' ,
            'description' => 'nullable|string|max:1000',
            'status' => 'in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The zone title is required.',
            'title.string' => 'The title must be a valid string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.unique' => 'This zone title already exists.',
            'description.string' => 'The description must be a valid string.',
            'description.max' => 'The description may not be greater than 1000 characters.',
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
