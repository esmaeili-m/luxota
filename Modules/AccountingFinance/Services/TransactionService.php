<?php

namespace Modules\AccountingFinance\Services;

use Modules\AccountingFinance\Repositories\TransactionRepository;

class TransactionService
{
    public TransactionRepository $repo;

    public function __construct(TransactionRepository $repo)
    {
        $this->repo=$repo;
    }
    public function get_transactions_user_wallet()
    {
        return $this->repo->get_transactions_user_wallet();
    }

    public function get_transactions_user($voucher_id)
    {
        $transactions=$this->repo->get_transactions_user($voucher_id);
        $balance = 0;
        return $transactions->map(function($t) use (&$balance) {
            $balance += ($t->credit ?? 0) - ($t->debit ?? 0);
            $t->balance = $balance;
            return $t;
        });

    }
}
