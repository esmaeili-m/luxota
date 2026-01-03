<?php

namespace Modules\AccountingFinance\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AccountingFinance\Database\factories\InvoiceFactory;
use Modules\Currency\App\Models\Currency;
use Modules\Product\App\Models\Product;
use Modules\User\App\Models\User;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function transactions_item()
    {
        return $this->hasMany(TransactionItem::class);
    }
    public function scopeSearch($query, $filters)
    {
        foreach ($filters ?? [] as $field => $value) {
            if ($value === null || $value === '') {
                break;
            }

            switch ($field) {

                case 'invoice_code':
                    $query->where('mahdi', $value);
                    break;
                case 'status':
                    $query->where('status', $value);
                    break;
            }
        }

        return $query;

    }

}
