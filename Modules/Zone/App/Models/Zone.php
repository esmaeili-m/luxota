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
}
