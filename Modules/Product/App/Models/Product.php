<?php

namespace Modules\Product\App\Models;

use App\Traits\LogsRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\AccountingFinance\App\Models\InvoiceItem;
use Modules\Category\App\Models\Category;
use Modules\Price\App\Models\Price;
use Modules\User\App\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity,LogsRelations;

    protected $guarded = [];

    protected static $recordEvents = ['created', 'updated', 'deleted', 'restored', 'forceDeleted'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('products')
            ->setDescriptionForEvent(function(string $eventName) {
                $userName = auth()->user()?->name ?? 'NOT FOUND';
                return "Product {$eventName} by {$userName}";
            });
    }

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'product_code' => 'integer',
        'last_version_update_date' => 'datetime',
        'version' => 'float',
        'show_price' => 'boolean',
        'payment_type' => 'boolean',
        'order' => 'integer',
    ];
    protected array $loggableRelations = [
        'category_id' => [
            'model' => Category::class,
            'key' => 'title',
            'label' => 'categor',
        ],
    ];

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function active_item()
    {
        return $this->hasOne(InvoiceItem::class)->where('status',0)->where('user_id',auth()?->user()?->id);
    }
    public function author()
    {
        return $this->hasOne(User::class,'id','author_id');

    }
    public function comments()
    {
        return $this->hasMany(Comment::class);

    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $newAttributes = $activity->properties['attributes'] ?? [];
        $oldAttributes = $activity->properties['old'] ?? [];

        $relationChanges = $this->getRelatedChanges();

        foreach ($relationChanges as $label => $change) {
            if (!is_array($change) || !array_key_exists('old', $change)) {
                continue;
            }

            $oldAttributes[$label] = $change['old'];
            $newAttributes[$label] = $change['new'];
        }

        // فقط attributes و old رو بازنویسی کن، بقیه ساختار دست نخورده
        $activity->properties = collect([
            'attributes' => $newAttributes,
            'old' => $oldAttributes,
        ]);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "product '{$this->name}' was {$eventName} by user ID " . auth()->id();
    }

}
