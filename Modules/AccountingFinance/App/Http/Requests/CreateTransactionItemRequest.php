<?php

namespace Modules\AccountingFinance\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\AccountingFinance\App\Models\TranactionItem;
use Modules\AccountingFinance\App\Models\Transaction;

class CreateTransactionItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'invoice_id' => 'required',
            'amount' => 'required|numeric',
            'method' => 'required|in:1,2',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = auth()->user();
            if ($this->input('method') == 1){
                $transactions= Transaction::where('user_id',$user->id)->where('method',1)->get();
                $transactions_item= TranactionItem::where('invoice_id',$this->input('invoice_id'))->where('method',1)->whereNull('voucher_id')->sum('amount');
                $remainder= ($transactions->sum('credit') - $transactions->sum('debit')) - $transactions_item;
                if($remainder < $this->input('amount')){
                    $validator->errors()->add('amount','The amount entered is greater than your wallet balance.');
                }
            }else{
                $transactions= Transaction::where('user_id',$user->id)->where('method',2)->where('voucher_id',$this->input('voucher_id'))->get();
                $transactions_item= TranactionItem::where('invoice_id',$this->input('invoice_id'))->where('method',2)->where('voucher_id',$this->input('voucher_id'))->sum('amount');
                $remainder= ($transactions->sum('credit') - $transactions->sum('debit')) - $transactions_item;
                if($remainder < $this->input('amount')){
                    $validator->errors()->add('amount','The amount entered is greater than your Voucher balance.');
                }
            }
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
