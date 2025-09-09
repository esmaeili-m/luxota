<?php

namespace Modules\AccountingFinance\Repositories;

use Modules\AccountingFinance\App\Models\InvoiceItem;

class InvoiceRepository
{
    public function add_item($data)
    {
        return InvoiceItem::create($data);
    }
    public function remove_item($id)
    {
        return InvoiceItem::find($id)->delete();
    }
}
