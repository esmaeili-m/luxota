<?php

namespace Modules\Category\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'title' => 'required|array|min:1',
            'title.*' => 'string|max:255',
            'subtitle.*' => 'string|max:500',
            'subtitle' => [
                'nullable',
                'array',
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
            'slug' => 'required|string|alpha_dash|max:255|unique:categories,slug',
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
            'slug.required' => 'The slug is required.',
            'slug.unique' => 'This slug has already been taken.',
            'slug.alpha_dash' => 'The slug must only contain letters, numbers, dashes and underscores.',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'parent_id.exists' => 'The selected parent category does not exist.',
        ];
    }
    protected function prepareForValidation()
    {
        // چک کن slug وجود داشته باشه
        if ($this->has('slug')) {
            $slug = $this->input('slug');

            // جایگزینی فاصله با خط زیر
            $cleanedSlug = str_replace(' ', '_', $slug);

            // ذخیره مقدار تمیز شده برای استفاده توی ولیدیشن
            $this->merge([
                'slug' => $cleanedSlug
            ]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
