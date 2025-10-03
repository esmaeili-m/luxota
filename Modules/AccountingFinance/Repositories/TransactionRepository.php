<?php

namespace Modules\AccountingFinance\Repositories;

use Modules\AccountingFinance\App\Models\Transaction;

class TransactionRepository
{
    public function get_transactions_user_wallet()
    {
        return Transaction::where('user_id',auth()->user()->id)->where('method',1)->get();
    }

    public function get_transactions_user($voucher_id)
    {
        return Transaction::where('voucher_id',$voucher_id)->where('user_id',auth()->user()->id)->where('method',2)->get();
    }

    public function create_transaction($data)
    {
        return Transaction::create($data);
    }
}
