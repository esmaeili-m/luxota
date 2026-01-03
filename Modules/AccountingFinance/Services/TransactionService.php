<?php

namespace Modules\AccountingFinance\Services;

use Illuminate\Validation\ValidationException;
use Modules\AccountingFinance\Repositories\InvoiceRepository;
use Modules\AccountingFinance\Repositories\TransactionRepository;

class TransactionService
{
    public TransactionRepository $repo;
    public InvoiceRepository $invoiceRepository;

    public function __construct(TransactionRepository $repo ,InvoiceRepository $invoiceRepository)
    {
        $this->repo=$repo;
        $this->invoiceRepository=$invoiceRepository;
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
    public function validateBalance(array $data, int $userId): int
    {
        $amount    = $data['amount'];
        $method    = $data['method'];
        $voucherId = $data['voucher_id'] ?? null;

        $invoice = $this->invoiceRepository->get_invoice($data['invoice_code']);

        if (!$invoice) {
            throw ValidationException::withMessages([
                'invoice_code' => 'Invoice not found.',
            ]);
        }

        // Collection (Eager Loaded)
        $items = $invoice->transactions_item;

        // مجموع پرداخت‌ها
        $walletPaid  = $items->where('method', 1)->sum('amount');
        $voucherPaid = $items->where('method', 2)->sum('amount');

        // مانده فاکتور
        $invoiceTotal = ($invoice->total_base ?? 0) + ($invoice->tax_amount_base ?? 0);
        $invoiceRemaining = max(0, $invoiceTotal - ($walletPaid + $voucherPaid));

        if ($amount > $invoiceRemaining) {
            throw ValidationException::withMessages([
                'amount' => 'The amount entered is greater than the invoice remaining amount.',
            ]);
        }

        // مانده حساب پرداخت‌کننده
        $balance = $this->repo->getUserBalance($userId, $method, $voucherId);

        $alreadyUsed = $method === 1
            ? $walletPaid
            : $voucherPaid;

        if ($amount > ($balance - $alreadyUsed)) {
            throw ValidationException::withMessages([
                'amount' => $method === 1
                    ? 'The amount entered is greater than your wallet balance.'
                    : 'The amount entered is greater than your voucher balance.',
            ]);
        }

        return $invoice->id;
    }
    public function create_tranaction_item($data)
    {
        return $this->repo->create_tranaction_item($data);
    }

    public function remove_tranaction_item($voucherId,$invoiceId)
    {
        return $this->repo->remove_tranaction_item($voucherId,$invoiceId);
    }
    public function remove_transaction_item_wallet($invoiceId)
    {
        return $this->repo->remove_transaction_item_wallet($invoiceId);
    }
}
