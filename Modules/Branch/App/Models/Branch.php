<?php

namespace Modules\Branch\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Branch\Database\factories\BranchFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Branch extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'status'
    ];
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
     * The attributes that should be cast.
     */
    protected $casts = [
    ];

    /**
     * Get the users that belong to this branch.
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
