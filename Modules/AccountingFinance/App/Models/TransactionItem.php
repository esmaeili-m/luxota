<?php

namespace Modules\AccountingFinance\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AccountingFinance\Database\factories\TranactionItemFactory;

class TransactionItem extends Model
{
    use HasFactory;
    protected $table='transaction_item';

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): TranactionItemFactory
    {
        //return TranactionItemFactory::new();
    }
}
