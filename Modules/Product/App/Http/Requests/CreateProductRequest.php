<?php

namespace Modules\Product\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:1',
            'title.*' => 'string|max:255',
            'description.*' => 'required|max:500',
            'description' => [
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
                            $message[] = "Missing languages in description: " . $missingInSubtitle->join(', ');
                        }

                        if ($extraInSubtitle->isNotEmpty()) {
                            $message[] = "Extra languages in description: " . $extraInSubtitle->join(', ');
                        }

                        $fail(implode('. ', $message));
                    }
                },
            ],
            'product_code' => 'nullable|integer|min:1',
            'last_version_update_date' => 'nullable|date',
            'version' => 'nullable|numeric|min:0',
            'image' => 'required',
            'video_script' => 'nullable|string',
            'order' => 'nullable|integer|min:1',
            'show_price' => 'nullable|boolean',
            'payment_type' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.en.required' => 'English title is required.',
            'title.fa.required' => 'Persian title is required.',
            'description.required' => 'Description is required.',
            'description.en.required' => 'English description is required.',
            'description.fa.required' => 'Persian description is required.',
            'product_code.integer' => 'Product code must be a number.',
            'product_code.min' => 'Product code must be at least 1.',
            'version.numeric' => 'Version must be a number.',
            'version.min' => 'Version must be at least 0.',
            'slug.unique' => 'This slug is already taken.',
            'order.integer' => 'Order must be a number.',
            'order.min' => 'Order must be at least 1.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category does not exist.',
        ];
    }
}
