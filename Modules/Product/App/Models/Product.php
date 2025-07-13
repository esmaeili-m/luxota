<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Category\App\Models\Category;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = [];
    
    protected $fillable = [
        'title',
        'description',
        'product_code',
        'last_version_update_date',
        'version',
        'image',
        'video_script',
        'slug',
        'order',
        'show_price',
        'payment_type',
        'status',
        'category_id',
    ];
    
    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'product_code' => 'integer',
        'last_version_update_date' => 'datetime',
        'version' => 'float',
        'show_price' => 'boolean',
        'payment_type' => 'boolean',
        'status' => 'boolean',
        'order' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted()
    {
        static::deleting(function ($product) {
            // Handle any product-specific deletion logic if needed
        });

        static::restoring(function ($product) {
            // Handle any product-specific restoration logic if needed
        });
    }
}
