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
    protected $guarded = [];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('branches')
            ->setDescriptionForEvent(function(string $eventName) {
                $userName = auth()->user()?->name ?? 'NOT FOUND';
                return "Branch {$eventName} by {$userName}";
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
    protected static function booted()
    {
        static::updated(function ($branch) {
            if ($branch->isDirty('status')) {
                foreach ($branch->users as $user) {
                    $user->status = $branch->status;
                    $user->save();
                }
            }
        });

        static::deleted(function ($branch) {
            foreach ($branch->users()->get() as $user) {
                $user->delete();
            }
        });

        static::restored(function ($branch) {
            foreach ($branch->users()->withTrashed()->get() as $user) {
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
