<?php

namespace Modules\User\App\Models;

use App\Traits\LogsRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Branch\App\Models\Branch;
use Modules\City\App\Models\City;
use Modules\Product\App\Models\Product;
use Modules\Rank\App\Models\Rank;
use Modules\Referrer\App\Models\Referrer;
use Modules\User\Database\factories\UserFactory;
use Modules\Zone\App\Models\Zone;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasFactory, SoftDeletes, HasApiTokens, Notifiable, LogsActivity, LogsRelations;

    protected $guard_name = 'api';

    protected $guarded = [];

    protected array $loggableRelations = [
        'rank_id' => [
            'model' => Rank::class,
            'key' => 'title',
            'label' => 'rank',
        ],
        'city_id' => [
            'model' => City::class,
            'key' => 'en',
            'label' => 'city',
        ],
        'zone_id' => [
            'model' => Zone::class,
            'key' => 'title',
            'label' => 'zone',
        ],
        'referrer_id' => [
            'model' => Referrer::class,
            'key' => 'title',
            'label' => 'referrer',
        ],
        'branch_id' => [
            'model' => Branch::class,
            'key' => 'title',
            'label' => 'branch',
        ],
        'parent_id' => [
            'model' => User::class,
            'key' => 'name',
            'label' => 'Parent',
        ],
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // روابط Eloquent

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function zone()
    {
        return $this->belongsTo(\Modules\Zone\App\Models\Zone::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }


    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function referrer()
    {
        return $this->belongsTo(self::class, 'referrer_id');
    }

    public function branch()
    {
        return $this->belongsTo(\Modules\Branch\App\Models\Branch::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function referredUsers()
    {
        return $this->hasMany(self::class, 'referrer_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'author_id');
    }

    // Factory

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('users')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $newAttributes = $activity->properties['attributes'] ?? [];
        $oldAttributes = $activity->properties['old'] ?? [];

        $relationChanges = $this->getRelatedChanges();

        // ریلیشن‌ها رو به attributes و old اضافه می‌کنیم
        foreach ($relationChanges as $label => $change) {
            if (!is_array($change) || !array_key_exists('old', $change)) {
                continue; // اگه ساختار درست نبود رد شو
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
        return "User '{$this->name}' was {$eventName} by user ID " . auth()->id();
    }

}
