<?php

namespace Modules\AccountingFinance\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\AccountingFinance\App\Models\UserVoucher;
use Modules\AccountingFinance\App\Models\Voucher;

class VoucherRepository
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Voucher::with(['user','createdBy'])->paginate($perPage);
    }

    public function create($data)
    {
        return Voucher::create($data);
    }

    public function find(int $id)
    {
        return Voucher::findOrFail($id);
    }

    public function update(Voucher $voucher, array $data): bool
    {
        return $voucher->update($data);
    }

    public function get_vouchers_user()
    {
        return UserVoucher::where('user_id',auth()->user()->id)->whereHas('voucher')->with(['voucher.transactions','transactions_temp'=>function ($query){
            $query->where('status',0);
        }])->latest()->get();
    }

    public function redeem_voucher($data)
    {
        return UserVoucher::create($data);
    }

    public function find_voucher_with_code($code)
    {
        return Voucher::where('code',$code)->first();
    }
}
