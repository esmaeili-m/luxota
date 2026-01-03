<?php

namespace Modules\Zone\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Zone\Database\factories\ZoneFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Zone extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    protected static $recordEvents = ['created', 'updated', 'deleted', 'restored', 'forceDeleted'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('zones')
            ->setDescriptionForEvent(function(string $eventName) {
                $userName = auth()->user()?->name ?? 'NOT FOUND';
                return "Zone {$eventName} by {$userName}";
            });
    }
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [];
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
    protected static function booted()
    {
        static::updated(function ($zone) {
            if ($zone->isDirty('status')) {
                foreach ($zone->users as $user) {
                    $user->status = $zone->status;
                    $user->save();
                }
            }
        });

        static::deleted(function ($zone) {
            foreach ($zone->users()->get() as $user) {
                $user->delete();
            }
        });

        static::restored(function ($zone) {
            foreach ($zone->users()->withTrashed()->get() as $user) {
                $user->restore();
            }
        });
    }
    public function scopeSearch($query, $fillters)
    {

        foreach ($fillters ?? [] as $filed){
            if (!$filed) {
                break;
            }
            switch ($filed) {
                case 'title':
                    $query->where('title', $filed);
                    break;

                case 'description':
                    $query->where('description', 'like', "%{$filed}%");
                    break;

                case 'status':
                    $query->where('status', $filed);
                    break;
            }
        }

        return $query;
    }
}
