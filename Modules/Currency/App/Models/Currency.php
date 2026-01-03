<?php

namespace Modules\Currency\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Currency\Database\factories\CurrencyFactory;

class Currency extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    protected static function newFactory(): CurrencyFactory
    {
        //return CurrencyFactory::new();
    }
}
