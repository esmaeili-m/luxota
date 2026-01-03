<?php

namespace Modules\AccountingFinance\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\AccountingFinance\App\Models\TransactionItem;
use Modules\AccountingFinance\App\Models\Transaction;

class CreateTransactionItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'invoice_code' => 'required',
            'amount' => 'required|numeric',
            'method' => 'required|in:1,2',
            'voucher_id' => 'nullable',
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
