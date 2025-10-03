<?php

namespace Modules\AccountingFinance\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Currency\App\Models\Currency;
use Modules\Product\App\Models\Product;

class InvoiceItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo(Product::class);

    }
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

}
