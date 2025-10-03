<?php

namespace Modules\AccountingFinance\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'remarks' => 'nullable',
            'quantits' => 'required',
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
