<?php

namespace Modules\Category\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\App\Models\Product;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    use LogsActivity;
    protected $guarded = [];
    protected $casts = [
      'title' => 'array',
      'subtitle' => 'array'
    ];
    protected static $recordEvents = ['created', 'updated', 'deleted', 'restored', 'forceDeleted'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('Categories')
            ->setDescriptionForEvent(function(string $eventName) {
                $userName = auth()->user()?->name ?? 'NOT FOUND';
                return "Category {$eventName} by {$userName}";
            });
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeSearch($query, $filters)
    {
        foreach ($filters ?? [] as $field => $value) {
            switch ($field) {

                case 'content':
                    $query->where(function($q) use ($value) {
                        $q->where('title', 'like', "%{$value}%")
                            ->orWhere('subtitle', 'like', "%{$value}%");
                    });
                    break;

                case 'parent_id':
                    if ($value === 'null' || $value === null) {
                        $query->whereNull('parent_id');
                    }elseif ($value === 'notNull') {
                        $query->whereNotNull('parent_id');
                    } else {
                        $query->where('parent_id', $value);
                    }
                    break;

                case 'status':
                    $query->where('status', (int)$value);
                    break;
            }
        }

        return $query;
    }

}
