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


    protected static function newFactory(): InvoiceFactory
    {
        //return InvoiceFactory::new();
    }
}
