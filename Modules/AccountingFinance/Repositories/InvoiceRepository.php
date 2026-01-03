<?php

namespace Modules\AccountingFinance\Repositories;

use Modules\AccountingFinance\App\Models\Invoice;
use Modules\AccountingFinance\App\Models\InvoiceItem;
use Modules\AccountingFinance\App\Models\UserVoucher;
use Modules\AccountingFinance\App\Models\Voucher;
use Modules\User\App\Models\User;

class InvoiceRepository
{

    public function getInvoices(array $filters = [], $perPage = 15, $paginate = true)
    {
        $query = Invoice::query()->with(['invoice_items.product','user','currency']);

        if (!empty($filters)) {
            $query->search($filters);
        }
        return $paginate
            ? $query->paginate($perPage)
            : $query->get();
    }
    public function get_items()
    {
        return InvoiceItem::where('user_id',auth()->user()->id)->where('status',0)->with(['currency'])->get();

    }
    public function add_item($data)
    {
        return InvoiceItem::create($data);
    }
    public function remove_item($id)
    {
        return InvoiceItem::find($id)->delete();
    }

    public function InvoiceItemCount(User $user)
    {
        return $user->invoiceItems()->where('status',0)->count();
    }

    public function createInvoice($data)
    {
        return Invoice::create($data);
    }

    public function updateInvoice($id,$data)
    {
        return Invoice::find($id)->update($data);

    }
    public function find($id)
    {
        return InvoiceItem::find($id);
    }

    public function update_invoiceItem(InvoiceItem $invoiceItem,$data)
    {
        return $invoiceItem->update($data);
    }
    public function get_max_invoice_code()
    {
        return Invoice::max('invoice_code');
    }
    public function get_max_invoice_id()
    {
        return Invoice::max('id');
    }

    public function get_invoice($invoice_code)
    {
        return Invoice::where('invoice_code',$invoice_code)->where('user_id',auth()->user()->id)->with(['user','currency','invoice_items','transactions_item'])->first();
    }
    public function get_invoice_by_id($invoiceId)
    {
        return Invoice::where('user_id',auth()->user()->id)->with(['user','currency','invoice_items','transactions_item'])->find($invoiceId);
    }
    public function get_invoice_with_transaction($invoice_code)
    {
        return Invoice::where('invoice_code',$invoice_code)->where('user_id',auth()->user()->id)->with(['user.vouchers.transactions','currency','invoice_items','transactions_item'])->first();
    }

    public function get_invoice_transactions($invoice_code)
    {
        return Invoice::where('invoice_code',$invoice_code)->where('user_id',auth()->user()->id)->with(['user','currency','invoice_items','transactions_item'])->first();

    }
    public function get_invoices_user()
    {
        return Invoice::where('user_id',auth()->user()->id)->with(['invoice_items.product','currency'])->latest()->get();
    }


}
