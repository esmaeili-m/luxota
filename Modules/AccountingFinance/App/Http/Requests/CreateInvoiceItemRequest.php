<?php

namespace Modules\AccountingFinance\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'duration' => ['required', 'in:1,3,6'],
            'product_id' => 'required'
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
