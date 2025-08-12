<?php

namespace Modules\Referrer\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReferrerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:referrers,title,' . $this->route('referrer'),
            'status' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The referrer title is required.',
            'title.string' => 'The title must be a valid string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.unique' => 'This referrer title already exists.',
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