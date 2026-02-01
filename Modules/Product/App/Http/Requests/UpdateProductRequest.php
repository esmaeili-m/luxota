<?php

namespace Modules\Product\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $productId = $this->route('id');

        return [
            'title' => 'sometimes|required|array',
            'title.en' => 'sometimes|required|string|max:255',

            'description' => 'sometimes|required|array',
            'description.en' => 'sometimes|required|string',

            'product_code' => 'sometimes|nullable|integer|min:1',
            'last_version_update_date' => 'sometimes|nullable|date',
            'version' => 'sometimes|nullable|numeric|min:0',

            'image' => 'sometimes|nullable', // اگه فایل هست → image|mimes هم اضافه کن
            'video_script' => 'sometimes|nullable|string',

            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($productId),
            ],

            'order' => 'sometimes|nullable|integer|min:1',

            'show_price' => 'sometimes|nullable|boolean',
            'payment_type' => 'sometimes|nullable|boolean',
            'status' => 'sometimes|nullable|boolean',

            'category_id' => 'sometimes|required|exists:categories,id',
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
