<?php

namespace Modules\AccountingFinance\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AccountingFinance\Database\factories\UserVoucherFactory;

class UserVoucher extends Model
{
    use HasFactory;
    protected  $table='users_vouchers';

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
    protected static function newFactory(): UserVoucherFactory
    {
        //return UserVoucherFactory::new();
    }
}
