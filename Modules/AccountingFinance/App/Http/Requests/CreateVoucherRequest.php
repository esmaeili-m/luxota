<?php

namespace Modules\AccountingFinance\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVoucherRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'code' => 'required|unique:vouchers,code,'. $this->route('voucher'),
            'expires_at' => 'required',
            'remark' => 'required',
            'amount' => 'required|numeric',
            'user_id' => 'nullable|exists:users,id',
            'redeemed_at' => 'required',
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
