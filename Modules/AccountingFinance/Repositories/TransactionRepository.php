<?php

namespace Modules\AccountingFinance\Repositories;

use Modules\AccountingFinance\App\Models\Transaction;
use Modules\AccountingFinance\App\Models\TransactionItem;

class TransactionRepository
{
    public function get_transactions_user_wallet()
    {
        return Transaction::where('user_id',auth()->user()->id)->where('method',1)->get();
    }
    public function getUserWalletBalance(int $userId): float
    {
        return Transaction::where('user_id', $userId)
            ->where('method', 1)
            ->selectRaw('COALESCE(SUM(credit),0) - COALESCE(SUM(debit),0) as balance')
            ->value('balance');
    }
    public function getUserVoucherBalance(int $userId, int $voucherId): float
    {
        return Transaction::where('user_id', $userId)
            ->where('method', 2)
            ->where('voucher_id', $voucherId)
            ->selectRaw('COALESCE(SUM(credit),0) - COALESCE(SUM(debit),0) as balance')
            ->value('balance');
    }


    public function get_transactions_user($voucher_id)
    {
        return Transaction::where('voucher_id',$voucher_id)->where('user_id',auth()->user()->id)->where('method',2)->get();
    }

    public function create_transaction($data)
    {
        return Transaction::create($data);
    }
    public function create($data)
    {
        return Transaction::create($data);
    }

    public function create_tranaction_item($data)
    {
        return TransactionItem::create($data);
    }
    public function update_tranaction_item($id,$data)
    {
        return TransactionItem::find($id)->update($data);
    }

    public function remove_tranaction_item($voucherId,$invoiceId)
    {
        return TransactionItem::where('voucher_id',$voucherId)->where('method',2)->where('invoice_id',$invoiceId)->delete();
    }
    public function remove_transaction_item_wallet($invoiceId)
    {
        return TransactionItem::where('invoice_id',$invoiceId)->where('method',1)->delete();
    }

    public function getUserBalance(int $userId, int $method, ?int $voucherId = null): float
    {
        $query = Transaction::where('user_id', $userId)
            ->where('method', $method);

        if ($method === 2 && $voucherId !== null) {
            $query->where('voucher_id', $voucherId);
        }

        return (float) $query
            ->selectRaw('COALESCE(SUM(credit),0) - COALESCE(SUM(debit),0) as balance')
            ->value('balance');
    }

}
