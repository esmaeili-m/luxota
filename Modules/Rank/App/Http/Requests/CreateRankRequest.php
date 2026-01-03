<?php

namespace Modules\Rank\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRankRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:ranks,title',
            'status' => 'in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The rank title is required.',
            'title.string' => 'The title must be a valid string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.unique' => 'This rank title already exists.',
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
