<?php

namespace Modules\AccountingFinance\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AccountingFinance\Database\factories\TransactionFactory;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): TransactionFactory
    {
        //return TransactionFactory::new();
    }
}
