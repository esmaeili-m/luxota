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
    protected $fillable = [
        'title',
        'status'
    ];

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
}
