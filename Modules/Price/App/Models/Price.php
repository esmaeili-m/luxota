<?php

namespace Modules\Price\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Price\Database\factories\PriceFactory;
use Modules\Product\App\Models\Product;
use Modules\Zone\App\Models\Zone;

class Price extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    protected static function newFactory(): PriceFactory
    {
        //return PriceFactory::new();
    }
}
