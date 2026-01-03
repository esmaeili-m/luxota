<?php

namespace Modules\Referrer\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Referrer\Database\factories\ReferrerFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Referrer extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    protected static $recordEvents = ['created', 'updated', 'deleted', 'restored', 'forceDeleted'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('referrers')
            ->setDescriptionForEvent(function(string $eventName) {
                $userName = auth()->user()?->name ?? 'NOT FOUND';
                return "Referrer {$eventName} by {$userName}";
            });
    }
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
    ];

    /**
     * Get the users that belong to this referrer type.
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }

    protected static function booted()
    {
        static::updated(function ($referrer) {
            if ($referrer->isDirty('status')) {
                foreach ($referrer->users as $user) {
                    $user->status = $referrer->status;
                    $user->save();
                }
            }
        });

        static::deleted(function ($referrer) {
            foreach ($referrer->users()->get() as $user) {
                $user->delete();
            }
        });

        static::restored(function ($referrer) {
            foreach ($referrer->users()->withTrashed()->get() as $user) {
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

                case 'status':
                    $query->where('status', $filed);
                    break;

            }
        }

        return $query;
    }
}
