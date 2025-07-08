<?php

namespace Modules\Category\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:1',
            'title.*' => 'string|max:255',
            'subtitle.*' => 'string|max:500',
            'subtitle' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) return;
                    $titleArray = $this->input('title');
                    $titleKeys = collect(array_keys($titleArray));
                    $subtitleKeys = collect(array_keys($value));
                    $missingInSubtitle = $titleKeys->diff($subtitleKeys);
                    $extraInSubtitle = $subtitleKeys->diff($titleKeys);

                    if ($missingInSubtitle->isNotEmpty() || $extraInSubtitle->isNotEmpty()) {
                        $message = [];

                        if ($missingInSubtitle->isNotEmpty()) {
                            $message[] = "Missing languages in subtitle: " . $missingInSubtitle->join(', ');
                        }

                        if ($extraInSubtitle->isNotEmpty()) {
                            $message[] = "Extra languages in subtitle: " . $extraInSubtitle->join(', ');
                        }

                        $fail(implode('. ', $message));
                    }
                },
            ],
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'title.array' => 'The title must be an array of localized strings.',
            'title.*.string' => 'Each title must be a valid string.',
            'subtitle.array' => 'Subtitle must be an array.',
            'subtitle.*.string' => 'Each subtitle must be a valid string.',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'parent_id.exists' => 'The selected parent category does not exist.',
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
