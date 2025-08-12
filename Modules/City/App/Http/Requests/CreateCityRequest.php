<?php

namespace Modules\City\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'country_id' => 'required|exists:countries,id',
            'en' => 'required|string|max:255',
            'abb' => 'required|string|max:10',
            'priority' => 'required|integer|min:0',
            'status' => 'boolean',
            'fa' => 'nullable|string|max:255',
            'ar' => 'nullable|string|max:255',
            'ku' => 'nullable|string|max:255',
            'tr' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'country_id.required' => 'The country is required.',
            'country_id.exists' => 'The selected country is invalid.',
            'en.required' => 'The English name is required.',
            'en.string' => 'The English name must be a valid string.',
            'en.max' => 'The English name may not be greater than 255 characters.',
            'abb.required' => 'The abbreviation is required.',
            'abb.string' => 'The abbreviation must be a valid string.',
            'abb.max' => 'The abbreviation may not be greater than 10 characters.',
            'priority.required' => 'The priority is required.',
            'priority.integer' => 'The priority must be a number.',
            'priority.min' => 'The priority must be at least 0.',
            'status.boolean' => 'The status must be true or false.',
            'fa.string' => 'The Persian name must be a valid string.',
            'fa.max' => 'The Persian name may not be greater than 255 characters.',
            'ar.string' => 'The Arabic name must be a valid string.',
            'ar.max' => 'The Arabic name may not be greater than 255 characters.',
            'ku.string' => 'The Kurdish name must be a valid string.',
            'ku.max' => 'The Kurdish name may not be greater than 255 characters.',
            'tr.string' => 'The Turkish name must be a valid string.',
            'tr.max' => 'The Turkish name may not be greater than 255 characters.',
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