<?php

namespace Modules\AccountingFinance\App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\AccountingFinance\App\Models\UserVoucher;
use Modules\AccountingFinance\App\Models\Voucher;

class RedeemVoucherRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => ['required'],
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $voucher = Voucher::where('code', $this->code)->first();
            $exsis=UserVoucher::where('user_id',auth()->user()->id)->where('voucher_id',$voucher->id)->first();
            if ($exsis){
                $validator->errors()->add('code', 'Already registered');
            }
            if (! $voucher) {
                $validator->errors()->add('code', 'The Provided Code Does Not Exist');
            } elseif ($voucher->expires_at < Carbon::now()) {
                $validator->errors()->add('code', 'This Code Has Expried.');
            }elseif (!is_null($voucher->user_id) && $voucher->user_id != auth()->user()->id){
                $validator->errors()->add('code', 'This Code Is Invalid.');

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
